<?php

namespace App\Http\Controllers\User;


use App\Helpers\GoogleAuthenticator;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Chat;
use App\Models\City;
use App\Models\Country;
use App\Models\Deposit;
use App\Models\Gateway;
use App\Models\Kyc as KYCModel;
use App\Models\Language;
use App\Models\Property;
use App\Models\State;
use App\Models\Transaction;
use App\Models\UserKyc;
use App\Models\UserLogin;
use App\Models\VendorInfo;
use App\Traits\Upload;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use PragmaRX\Google2FA\Google2FA;


class HomeController extends Controller
{
    use Upload;
    public function saveToken(Request $request)
    {
        try {
            Auth::user()
                ->fireBaseToken()
                ->create([
                    'token' => $request->token,
                ]);
            return response()->json([
                'msg' => 'token saved successfully.',
            ]);
        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }


    public function index()
    {
        $data['user'] = Auth::user();
        $data['firebaseNotify'] = config('firebase');


        if (auth()->user()->role == 1) {
            $vendorInfo = auth()->user()->vendorInfo;
            $data['as_vendorDated'] = $vendorInfo->created_at->format('F Y');

            $today = Carbon::today()->toDateString();
            $lastMonthStart = Carbon::now()->subMonth()->startOfMonth()->toDateString();
            $lastMonthEnd   = Carbon::now()->subMonth()->endOfMonth()->toDateString();
            $thisMonthStart = Carbon::now()->startOfMonth()->toDateString();
            $thisMonthEnd   = Carbon::now()->endOfMonth()->toDateString();

            $bookingStats = Booking::selectRaw("
                COUNT(CASE WHEN status != 0 THEN 1 END) as total_bookings,
                COUNT(CASE WHEN status = 1 AND check_in_date > ? THEN 1 END) as upcoming_bookings,
                COUNT(CASE WHEN status = 1 AND ? BETWEEN check_in_date AND check_out_date THEN 1 END) as running_bookings,
                COUNT(CASE WHEN status = 3 THEN 1 END) as completed_bookings,
                COUNT(CASE WHEN status = 4 THEN 1 END) as paid_bookings,
                COUNT(CASE WHEN status = 2 THEN 1 END) as cancelled_bookings,
                COUNT(CASE WHEN created_at BETWEEN ? AND ? THEN 1 END) as last_month_total,
                SUM(CASE WHEN status NOT IN (0,2,5) AND created_at BETWEEN ? AND ? THEN host_received ELSE 0 END) as this_month_host_received,
                SUM(CASE WHEN status NOT IN (0,2,5) AND created_at BETWEEN ? AND ? THEN host_received ELSE 0 END) as last_month_host_received,

                -- refunded bookings (all time)
                COUNT(CASE WHEN status = 5 THEN 1 END) as refunded_bookings
            ", [
                $today, $today,
                $lastMonthStart, $lastMonthEnd,
                $thisMonthStart, $thisMonthEnd,
                $lastMonthStart, $lastMonthEnd
            ])
                ->whereHas('property', function ($query) {
                    $query->where('host_id', auth()->id());
                })
                ->first();

            $data['totalBookings']         = $bookingStats->total_bookings;
            $data['upcomingBookings']      = $bookingStats->upcoming_bookings;
            $data['runningBookings']       = $bookingStats->running_bookings;
            $data['completedBookings']     = $bookingStats->completed_bookings;
            $data['cancelledBookings']     = $bookingStats->cancelled_bookings;
            $data['paidBookings']          = $bookingStats->paid_bookings;
            $data['thisMonthHostReceived'] = $bookingStats->this_month_host_received;
            $data['lastMonthHostReceived'] = $bookingStats->last_month_host_received;
            $data['refundedBookings']      = $bookingStats->refunded_bookings;

            $data['upcomingPercentage'] = $bookingStats->last_month_total > 0
                ? round(($bookingStats->upcoming_bookings / $bookingStats->last_month_total) * 100, 2)
                : 0;

            $data['hostReceivedGrowth'] = $bookingStats->last_month_host_received > 0
                ? round((($bookingStats->this_month_host_received - $bookingStats->last_month_host_received) / $bookingStats->last_month_host_received) * 100, 2)
                : 100;

            $data['refundedPercentage'] = $bookingStats->total_bookings > 0
                ? round(($bookingStats->refunded_bookings / $bookingStats->total_bookings) * 100, 2)
                : 0;

            $data['cancelledPercentage'] = $bookingStats->total_bookings > 0
                ? round(($bookingStats->cancelled_bookings / $bookingStats->total_bookings) * 100, 2)
                : 0;

            $data['completedPercentage'] = $bookingStats->total_bookings > 0
                ? round(($bookingStats->completed_bookings / $bookingStats->total_bookings) * 100, 2)
                : 0;

            $data['transactions'] = Transaction::where('user_id', auth()->id())->orWhere('host_id', auth()->id())->latest()->take(2)->get();
            $data['upComingBookings'] = Booking::with(['guest', 'property'])
                ->whereHas('property', function ($query) {
                    $query->where('host_id', auth()->id());
                })
                ->where('status', 1)
                ->whereDate('check_in_date', '>=', now()->toDateString())
                ->latest('check_in_date')
                ->take(4)
                ->get();
        }

        return view(template() . (auth()->user()->role == 1 ? 'vendor.dashboard' : 'user.dashboard'), $data);
    }

    public function transaction(Request $request)
    {
        try {
            $authId = auth()->id();

            $transactions = Transaction::with('transactional')
                ->where(function ($q) use ($authId) {
                    $q->where('user_id', $authId)->orWhere('host_id', $authId);
                })
                ->when($request->transaction_id, fn($q, $trx) => $q->where('trx_id', $trx))
                ->when($request->datefilter, function ($q, $range) {
                    $dates = explode(' - ', $range);
                    if (count($dates) === 2) {
                        $start = \Carbon\Carbon::createFromFormat('d/m/Y', $dates[0])->startOfDay();
                        $end = \Carbon\Carbon::createFromFormat('d/m/Y', $dates[1])->endOfDay();
                        $q->whereBetween('created_at', [$start, $end]);
                    }
                })
                ->orderByDesc('id')
                ->paginate(basicControl()->paginate);

            return view(template() . 'user.transaction.transaction', compact('transactions'));
        }catch (\Exception $exception){
            return back()->with('error', $exception->getMessage());
        }
    }

    public function profile()
    {
        return view(template() . 'user.profile.account');
    }
    public function profileDetails()
    {
        $data['missing'] = false;

        $kycTypes = KYCModel::where('status', 1)
            ->where('apply_for', '!=', 1)
            ->pluck('id');

        if ($kycTypes->isNotEmpty()) {
            $userKyc = UserKyc::where('user_id', Auth::id())
                ->whereIn('kyc_id', $kycTypes)
                ->get();

            $userKycIds = $userKyc->pluck('kyc_id')->toArray();

            $missingKycTypes = array_diff($kycTypes->toArray(), $userKycIds);
            if (!empty($missingKycTypes)) {
                $data['missing'] = true;
            }

            $statuses = $userKyc->pluck('status')->toArray();
            if (!in_array(1, $statuses)) {
                $data['missing'] = true;
            }
        }

        return view(template() . 'user.profile.partials.profile', $data);
    }
    public function personalCreate()
    {
        $data = [];
        if (auth()->user()->language_id){
            $data['userLanguage'] = Language::where('id', auth()->user()->language_id)->first();
        }

        return view(template() . 'user.profile.partials.create_profile', $data);
    }
    public function personalInfo()
    {
        return view(template() . 'user.profile.partials.my_profile');
    }
    public function loginSecurity ()
    {

        $data['loginHistory'] = UserLogin::select(['id','user_id','browser','os','get_device','created_at'])->where('user_id', auth()->id())->latest()->take(3)->get();
        return view(template() . 'user.profile.partials.login_security', $data);
    }

    public function personalInfoUpdate(Request $request)
    {
        $request->validate([
            'type' => 'required|string',
            'value' => 'nullable|string',
        ]);

        $user = auth()->user();

        $allowedFields = ['firstname', 'lastname', 'email', 'phone', 'address_one', 'address_two', 'city', 'state', 'zip_code', 'country'];
        if (!in_array($request->type, $allowedFields)) {
            return response()->json(['error' => 'Invalid field'], 422);
        }

        $user->{$request->type} = $request->value;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Profile Updated Successfully'
        ]);
    }

    public function profileUpdateImage(Request $request)
    {
        $allowedExtensions = ['jpg', 'png', 'jpeg'];
        $image = $request->image;

        $this->validate($request, [
            'image' => [
                'required',
                'max:4096',
                function ($fail) use ($image, $allowedExtensions) {
                    $ext = strtolower($image->getClientOriginalExtension());
                    if (($image->getSize() / 1000000) > 2) {
                        throw ValidationException::withMessages(['image' => "Images MAX 2MB ALLOWED!"]);
                    }
                    if (!in_array($ext, $allowedExtensions)) {
                        throw ValidationException::withMessages(['image' => "Only PNG, JPG, JPEG images are allowed"]);
                    }
                }
            ]
        ]);

        $user = Auth::user();

        if ($request->hasFile('image')) {
            $uploaded = $this->fileUpload(
                $request->image,
                config('filelocation.userProfile.path'),
                null, null,
                'webp',
                80,
                $user->image,
                $user->image_driver
            );

            if ($uploaded) {
                $user->image = $uploaded['path'];
                $user->image_driver = $uploaded['driver'];
            }
        }

        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Profile image updated successfully',
            'image_url' => getFile($user->image_driver, $user->image)
        ]);
    }

    public function profileUpdate(Request $request)
    {
        $request->validate([
            'type' => 'required|string',
            'value' => 'nullable|string',
        ]);

        $vendorInfo = auth()->user()->vendorInfo;

        if (!$vendorInfo) {
            $vendorInfo = new VendorInfo();
            $vendorInfo->vendor_id = auth()->id();
        }

        $vendorInfo->{$request->type} = $request->type == 'skills' ? $request->skill_value : $request->value;
        $vendorInfo->save();

        return response()->json(['success' => true]);
    }
    public function basicProfileUpdate(Request $request)
    {
        $request->validate([
            'type' => 'required|string',
            'value' => 'nullable|string',
        ]);

        $user = auth()->user();
        $user->{$request->type} = $request->value;
        $user->save();

        return response()->json(['success' => true]);
    }

    public function profilePhoneUpdate(Request $request)
    {
        $request->validate([
            'type' => 'required|string',
            'phone_code' => 'nullable|string',
            'phone' => 'nullable|string',
        ]);

        $user = auth()->user();
        $user->phone_code = $request->phone_code;
        $user->phone = $request->phone;
        $user->save();

        return response()->json(['success' => true]);
    }


    public function updatePassword(Request $request)
    {
        $rules = [
            'current_password' => "required",
            'password' => "required|min:5|confirmed",
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        $user = Auth::user();
        try {
            if (Hash::check($request->current_password, $user->password)) {
                $user->password = bcrypt($request->password);
                $user->password_updated = now();
                $user->save();
                return back()->with('success', 'Password Changes successfully.');
            } else {
                throw new \Exception('Current password did not match');
            }
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
    public function toggleStatus(Request $request)
    {
        $user = Auth::user();
        $user->status = $user->status == 2 ? 1 : 2;
        $user->save();

        return back()->with('success', 'Account status updated successfully.');
    }

    public function calender()
    {
        $userId = auth()->id();

        $bookings = Booking::with(['guest', 'property.pricing'])
            ->whereHas('property', function ($q) use ($userId) {
                $q->where('host_id', $userId);
            })
            ->whereIn('status', [1, 3])
            ->get();

        $events = $bookings->map(function ($booking) {
            return [
                'id' => 'booking_' . $booking->id,
                'name' => 'Booking: ' . optional($booking->guest)->firstname,
                'date' => [
                    \Carbon\Carbon::parse($booking->check_in_date)->format('F/d/Y'),
                    \Carbon\Carbon::parse($booking->check_out_date)->format('F/d/Y'),
                ],
                'description' => optional($booking->property)->title,
                'type' => 'event',
                'color' => '#3498db',
                'booking_id' => $booking->id,
                'property_id' => $booking->property_id,
            ];
        });

        return view(template() . 'vendor.calender', [
            'events' => $events
        ]);
    }
    public function messages(Request $request)
    {
        $allChat = Chat::with(['sender', 'receiver','property'])
            ->whereNull('chat_id')
            ->where(function ($query) {
                $authId = auth()->id();
                $query->where('user_id', $authId)
                    ->orWhere('sender_id', $authId)
                    ->orWhere('receiver_id', $authId);
            })
            ->orderBy('id', 'desc')
            ->get();

        $chat = Chat::with(['sender','receiver','reply','attachment','property'])
            ->where('booking_uid', $request->booking_uid)
            ->where('user_id', auth()->id())
            ->orWhere('receiver_id', auth()->id())
            ->whereNull('chat_id')
            ->first();

        return view(template() . 'user.chats.view', compact('chat','allChat'));
    }
    public function earnings(Request $request)
    {
        $month = $request->month;
        $year = $request->year;

        $data['earnings'] = Transaction::where(function ($q) {
                $q->where('user_id', auth()->id())
                    ->orWhere('host_id', auth()->id());
            })
            ->when(isset($month) && $month != 'All Months', function ($q) use ($month) {
                $q->whereMonth('created_at', $month);
            })
            ->when(isset($year) && $year != 'All Years', function ($q) use ($year) {
                $q->whereYear('created_at', $year);
            })
            ->latest()
            ->paginate(basicControl()->paginate);

        $data['months'] = collect(range(1, 12))->mapWithKeys(function ($m) {
            return [$m => \Carbon\Carbon::create()->month($m)->format('F')];
        })->toArray();

        $currentYear = now()->year;
        $data['years'] = collect(range($currentYear, $currentYear - 50))
            ->mapWithKeys(fn($y) => [$y => $y])
            ->toArray();

        return view(template() . 'vendor.earnings', $data);
    }
    public function hostDashTransaction(Request $request)
    {
        $range = $request->input('range', 'last30');

        $query = Transaction::where('user_id', auth()->id())
            ->orWhere('host_id', auth()->id());

        if ($range === 'last7') {
            $query->where('created_at', '>=', now()->subDays(7));
        } elseif ($range === 'last30') {
            $query->where('created_at', '>=', now()->subDays(30));
        } elseif ($range === 'last90') {
            $query->where('created_at', '>=', now()->subDays(90));
        }

        $transactions = $query->latest()->take(2)->get();

        return response()->json([
            'transactions' => $transactions->map(function ($tx) {
                $forTransaction = match ($tx->transactional_type) {
                    'App\Models\Booking' => 'Booking',
                    'App\Models\Payout' => 'Payout',
                    'App\Models\AffiliateStatistics' => 'Affiliate Earnings',
                    default => 'Other',
                };

                return [
                    'trx_id'          => $tx->trx_id,
                    'amount'          => $tx->amount,
                    'charge'          => $tx->charge,
                    'trx_type'        => $tx->trx_type,
                    'for_transaction' => $forTransaction,
                    'remarks'         => $tx->remarks,
                    'status'          => $tx->status,
                    'created_at'      => $tx->created_at->toDateTimeString(),
                ];
            }),
        ]);
    }

    public function reservations(Request $request)
    {
        $filter = $request->get('filter');

        $query = Booking::with(['property'])
            ->where(function ($query) {
                $query->where('guest_id', auth()->id())
                    ->orWhereHas('property', function ($q) {
                        $q->where('host_id', auth()->id());
                    });
            })
            ->where('status', '!=', 0);

        if ($filter === 'upcoming') {
            $query->whereIn('status', [1, 4]);
        } elseif ($filter === 'completed') {
            $query->where('status', 3);
        } elseif ($filter === 'canceled') {
            $query->whereIn('status', [2, 5]);
        }

        $data['bookings'] = $query->latest()->paginate(basicControl()->paginate);
        $data['currentFilter'] = $filter;

        return view(template() . 'user.reservation.lists', $data);
    }
    public function getCities(Request $request)
    {
        $query = $request->input('q');
        $offset = $request->input('offset', 0);
        $limit = 10;

        $cities = City::when($query, function($q) use ($query) {
            $q->where('name', 'like', '%' . $query . '%');
        })
            ->select('id', 'name')
            ->offset($offset)
            ->limit($limit)
            ->get();

        return response()->json($cities);
    }
    public function getLocations(Request $request)
    {
        $type = $request->input('type', 'country');
        $query = $request->input('q');
        $offset = $request->input('offset', 0);
        $limit = 10;

        $model = match (strtolower($type)) {
            'state' => State::query(),
            'city' => City::query(),
            default => Country::query(),
        };

        $data = $model->when($query, fn($q) => $q->where('name', 'like', "%{$query}%"))
            ->select('id', 'name')
            ->offset($offset)
            ->limit($limit)
            ->get();

        return response()->json($data);
    }
    public function getLanguage()
    {
        $data = Language::where('status', 1)->get();
        foreach ($data as $lang) {
            $lang->imageurl = getFile($lang->flag_driver, $lang->flag);
        }

        return response()->json($data);
    }

    public function paymentHistory(Request $request)
    {
        try {
            $payments = Deposit::with(['depositable', 'gateway'])
                ->where('user_id', auth()->id())
                ->orderBy('id', 'desc')
                ->when($request->transaction_id, fn($q, $trx) => $q->where('trx_id', $trx))
                ->when($request->datefilter, function ($q, $range) {
                    $dates = explode(' - ', $range);
                    if (count($dates) === 2) {
                        $start = \Carbon\Carbon::createFromFormat('d/m/Y', $dates[0])->startOfDay();
                        $end = \Carbon\Carbon::createFromFormat('d/m/Y', $dates[1])->endOfDay();
                        $q->whereBetween('created_at', [$start, $end]);
                    }
                })
                ->latest()->paginate(basicControl()->paginate);

            return view(template() . 'user.payment.index', compact('payments'));
        }catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function getYearToDateSummary()
    {
        $userId = auth()->id();
        $startOfYear = now()->startOfYear();
        $today = now();

        $bookings = Booking::whereHas('property', function ($q) use ($userId) {
            $q->where('host_id', $userId);
        })
            ->whereIn('status', [1, 3, 4])
            ->whereBetween('created_at', [$startOfYear, $today])
            ->get();

        $grossEarnings = $bookings->sum('amount_without_discount');

        $discounts = $bookings->sum('discount_amount') ?? 0;
        $serviceFee = $bookings->sum('site_charge') ?? 0;
        $received = $bookings->sum('host_received') ?? 0;
        $total = $bookings->sum('total_amount') ?? 0;

        return response()->json([
            'date_range' => $startOfYear->format('M j') . ' – ' . $today->format('M j, Y'),
            'gross_earnings' => number_format($grossEarnings, 2),
            'discounts' => number_format($discounts, 2),
            'service_fee' => number_format($serviceFee, 2),
            'received' => number_format($received, 2),
            'total' => number_format($total, 2),
        ]);
    }

    public function reservationDataFetch(Request $request)
    {
        $status = $request->status;
        $perPage = basicControl()->paginate;

        $query = Booking::with(['guest', 'property.photos'])
            ->whereHas('property', function ($query) {
                $query->where('host_id', auth()->id());
            });

        switch ($status) {
            case 'check_out':
                $query->where('status', 3);
                break;
            case 'current':
                $query->where('status', 1)
                    ->whereDate('check_in_date', '<=', now())
                    ->whereDate('check_out_date', '>=', now());
                break;
            case 'paid':
                $query->where('status', 4);
                break;
            case 'upcoming':
                $query->where('status', 1)
                    ->whereDate('check_in_date', '>', now());
                break;
            case 'canceled':
                $query->where('status', 2);
                break;
            default:
                return response()->json(['bookings' => []]);
        }

        $bookings = $query->latest()
            ->take($perPage)
            ->get()
            ->map(function ($booking) {
                $userInfo = is_string($booking->user_info) ? json_decode($booking->user_info, true) : ($booking->user_info ?? []);

                if (!empty($userInfo['adult']) && is_array($userInfo['adult'])) {
                    $userInfo['adult'] = array_map(function ($adult) {
                        $adult['image_url'] = isset($adult['image']['driver'], $adult['image']['path'])
                            ? getFile($adult['image']['driver'], $adult['image']['path'])
                            : null;
                        return $adult;
                    }, $userInfo['adult']);
                }

                if (!empty($userInfo['children']) && is_array($userInfo['children'])) {
                    $userInfo['children'] = array_map(function ($child) {
                        $child['image_url'] = isset($child['image']['driver'], $child['image']['path'])
                            ? getFile($child['image']['driver'], $child['image']['path'])
                            : null;
                        return $child;
                    }, $userInfo['children']);
                }

                $propertyImage = null;
                if ($booking->property && $booking->property->photos) {
                    try {
                        $propertyImage = getFile(
                            $booking->property->photos->images['thumb']['driver'],
                            $booking->property->photos->images['thumb']['path']
                        );
                    } catch (\Exception $e) {
                        $propertyImage = null;
                    }
                }

                return [
                    'id' => $booking->id,
                    'check_in_date' => dateTime($booking->check_in_date),
                    'check_out_date' => dateTime($booking->check_out_date),
                    'total_amount' => currencyPosition($booking->total_amount),
                    'status' => $booking->status,
                    'guest' => [
                        'firstname' => $booking->guest->firstname ?? '',
                        'lastname' => $booking->guest->lastname ?? '',
                        'image_url' => $booking->guest->image ? getFile($booking->guest->image_driver, $booking->guest->image) : null,
                        'address' => implode(', ', array_filter([
                            $booking->guest->city ?? null,
                            $booking->guest->state ?? null,
                            $booking->guest->country ?? null,
                        ])),
                        'email' => $booking->guest->email ?? null,
                        'phone' => $booking->guest->phone ?? null,
                    ],
                    'user_info' => $userInfo,
                    'host_received' => currencyPosition($booking->host_received) ?? 0,
                    'site_charge' => currencyPosition($booking->site_charge) ?? 0,
                    'discount_amount' => currencyPosition($booking->discount_amount) ?? 0,
                    'property' => [
                        'title' => $booking->property->title ?? '',
                        'address' => $booking->property->address ?? '',
                        'image_url' => $propertyImage,
                    ],
                ];
            });

        return response()->json([
            'bookings' => $bookings,
            'has_more' => $query->count() > $perPage
        ]);
    }

    public function chartDataFetch(Request $request)
    {
        $hostId = auth()->id();
        $range = (int) $request->input('range', 30);

        $labels = [];
        $values = [];

        for ($i = 0; $i < $range; $i++) {
            $date = Carbon::now()->subDays($range - 1 - $i);
            $labels[] = $date->format('j F');

            $count = Booking::whereHas('property', function ($q) use ($hostId) {
                $q->where('host_id', $hostId);
            })
                ->where('status', '!=', 0)
                ->whereDate('created_at', $date->toDateString())
                ->sum('host_received');

            $values[] = $count;
        }

        return response()->json([
            'labels' => $labels,
            'values' => $values,
        ]);
    }


    public function getEarningsData()
    {
        $userId = auth()->id();
        $year = now()->year;

        $monthlyEarnings = Booking::selectRaw('MONTH(bookings.created_at) as month, SUM(bookings.total_amount) as total')
            ->whereHas('property', function ($q) use ($userId) {
                $q->where('host_id', $userId);
            })
            ->whereIn('status', [3, 4])
            ->whereYear('created_at', $year)
            ->groupBy('month')
            ->pluck('total', 'month')
            ->toArray();

        $months = [];
        $values = [];

        foreach (range(1, 12) as $month) {
            $monthName = Carbon::create()->month($month)->format('M');
            $months[] = $monthName;
            $values[] = $monthlyEarnings[$month] ?? 0;
        }

        return response()->json([
            'labels' => $months,
            'data' => $values,
        ]);
    }

    public function calenderBooking($id)
    {
        try {
            $booking = Booking::with('property.pricing')->findOrFail($id);

            if (!$booking->property || !$booking->property->pricing) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pricing information not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'nightly_rate' => $booking->property->pricing->nightly_rate ?? 0,
                'weekly_rate' => $booking->property->pricing->weekly_rate ?? 0,
                'monthly_rate' => $booking->property->pricing->monthly_rate ?? 0,
                'cleaning_fee' => $booking->property->pricing->cleaning_fee ?? 0,
                'service_fee' => $booking->property->pricing->service_fee ?? 0,
                'refundable' => ($booking->property->pricing->refundable == 1) ? 'Yes' : 'No',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Booking not found'
            ], 404);
        }
    }
}
