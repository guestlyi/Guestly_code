<?php

namespace App\Http\Controllers\Admin\Affiliate;

use App\Http\Controllers\Controller;
use App\Mail\SendMail;
use App\Models\Affiliate;
use App\Models\AffiliateStatistics;
use App\Models\Language;
use App\Models\Payout;
use App\Models\PayoutMethod;
use App\Models\Transaction;
use App\Traits\Notify;
use App\Traits\Upload;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Yajra\DataTables\Facades\DataTables;

class ProfileManagement extends Controller
{
    use Upload, Notify;

    public function profile($id){
        try {
            $affiliate = Affiliate::where('id', $id)->firstOr(function () {
                throw new \Exception('No affiliate data found.');
            });

            $data['balance'] = $affiliate->balance ?? 0.00;
            $data['today_earning'] = Transaction::where('user_id', $id)
                ->where('transactional_type', AffiliateStatistics::class)
                ->whereDate('created_at', Carbon::today())
                ->sum('amount') ?? 0.00;
            $data['total_click'] = $affiliate->total_click ?? 0;

            return view('admin.affiliate_management.profile', $data, compact('affiliate'));
        }catch (\Exception $e){
            return back()->with('error', $e->getMessage());
        }
    }

    public function edit($id){
        $data['affiliate'] = Affiliate::where('id', $id)->first();
        $data['languages'] = Language::where('status', 1)->get();

        return view('admin.affiliate_management.manage.edit', $data);
    }

    public function imageUpdate(Request $request, $id)
    {
        $affiliate = Affiliate::findOrFail($id);

        $rules = [
            'profileImage' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ];

        $request->validate($rules);

        $messages = [];

        if ($request->hasFile('profileImage')) {
            $avatar = $request->file('profileImage');
            $avatarPath = $this->fileUpload(
                $avatar,
                config('filelocation.affiliate.path'),
                null,
                config('filelocation.affiliate.size'),
                'webp',
                60,
                $affiliate->image ?? null,
                $affiliate->image_driver ?? null
            );

            $affiliate->image_driver = $avatarPath['driver'] ?? 'local';
            $affiliate->image = $avatarPath['path'] ?? $affiliate->image;
            $messages[] = 'Profile image updated successfully.';
        }

        if (!empty($messages)) {
            $affiliate->save();
        }

        return response()->json([
            'success' => true,
            'message' => implode(' ', $messages) ?: 'No changes made.',
        ]);
    }

    public function basicUpdate(Request $request, $id){
        $request->validate([
            'firstname'     => 'required|string|max:100',
            'lastname'      => 'required|string|max:100',
            'username'      => 'required|string|max:100|alpha_dash|unique:affiliates,username,' . $id,
            'country_code'  => 'required|string|size:2',
            'phone_code'  => 'required|string',
            'phone'         => 'required|string|max:20',
            'country'       => 'required|string|max:100',
            'city'          => 'required|string|max:100',
            'state'         => 'required|string|max:100',
            'address_one'   => 'required|string|max:255',
            'address_two'   => 'nullable|string|max:255',
            'zip_code'      => 'required|string|max:20',
        ]);

        try {
            $affiliate = Affiliate::where('id', $id)->firstOr(function () {
                throw new \Exception('No affiliate data found.');
            });

            $affiliate->firstname = $request->firstname;
            $affiliate->lastname = $request->lastname;
            $affiliate->username = $request->username;
            $affiliate->country_code = $request->country_code;
            $affiliate->phone = $request->phone;
            $affiliate->phone_code = $request->phone_code;
            $affiliate->country = $request->country;
            $affiliate->city = $request->city;
            $affiliate->state = $request->state;
            $affiliate->address_one = $request->address_one;
            $affiliate->address_two = $request->address_two;
            $affiliate->zip_code = $request->zip_code;
            $affiliate->save();

            return back()->with('success', 'Affiliate profile updated successfully.');
        }catch (\Exception $e){
            return back()->with('error', $e->getMessage());
        }
    }

    public function emailUpdate(Request $request, $id){
        $request->validate([
            'email' => 'required|email|max:200|unique:affiliates,email,' . $id,
        ]);

        try {
            $affiliate = Affiliate::where('id', $id)->firstOr(function () {
                throw new \Exception('No affiliate data found.');
            });

            $affiliate->email = $request->email;
            $affiliate->save();

            return back()->with('success', 'Affiliate email updated successfully.');
        }catch (\Exception $e){
            return back()->with('error', $e->getMessage());
        }
    }


    public function passwordUpdate(Request $request, $id)
    {
        $request->validate([
            'newPassword'          => ['required', 'string', 'min:8', 'different:currentPassword'],
            'confirmNewPassword'   => 'required|same:newPassword',
        ]);

        try {
            $affiliate = Affiliate::where('id', $id)->firstOr(function () {
                throw new \Exception('No affiliate data found.');
            });

            if (basicControl()->strong_password) {
                $passwordRules = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*[\d\s\W]).{8,}$/';
                if (!preg_match($passwordRules, $request->newPassword)) {
                    return back()->with('error', 'Password must include uppercase, lowercase, number/symbol, and be at least 8 characters.');
                }
            }

            $affiliate->password = Hash::make($request->newPassword);
            $affiliate->save();

            return back()->with('success', 'Password updated successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function preferencesUpdate(Request $request, $id){
        $request->validate([
            'language' => 'required|string|max:100',
            'time_zone' => 'required',
        ]);

        try {
            $affiliate = Affiliate::where('id', $id)->firstOr(function () {
                throw new \Exception('No affiliate data found.');
            });

            $affiliate->language = $request->language;
            $affiliate->time_zone = $request->time_zone;
            $affiliate->save();

            return back()->with('success', 'Affiliate Time Zone And Language updated successfully.');
        }catch (\Exception $e){
            return back()->with('error', $e->getMessage());
        }
    }

    public function delete(Request $request, $id){
        try {
            $affiliate = Affiliate::where('id', $id)->firstOr(function () {
                throw new \Exception('No affiliate data found.');
            });

            $affiliate->delete();

            return redirect()->route('admin.affiliate.list')->with('success', 'Affiliate deleted successfully.');
        }catch (\Exception $e){
            return back()->with('error', $e->getMessage());
        }
    }
    public function status(Request $request, $id){
        try {
            $affiliate = Affiliate::where('id', $id)->firstOr(function () {
                throw new \Exception('No affiliate data found.');
            });

            $affiliate->status = ($affiliate->status == 1) ? 0 : 1;
            $affiliate->save();

            return redirect()->route('admin.affiliate.list')->with('success', 'Affiliate Status Changed successfully.');
        }catch (\Exception $e){
            return back()->with('error', $e->getMessage());
        }
    }

    public function sendMail($id)
    {
        try {
            $affiliate = Affiliate::where('id', $id)->firstOr(function () {
                throw new \Exception('No Affiliate found.');
            });
            return view('admin.affiliate_management.send_mail_form', compact('affiliate'));
        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    public function sendMailConfirm(Request $request, $id = null){
        $request->validate([
            'subject' => 'required|min:5',
            'description' => 'required|min:10',
        ]);

        try {

            $user = Affiliate::where('id', $id)->first();

            $subject = $request->subject;
            $template = $request->description;

            if (isset($user)) {
                Mail::to($user)->send(new SendMail(basicControl()->sender_email, $subject, $template));
            } else {
                $users = Affiliate::all();
                foreach ($users as $user) {
                    Mail::to($user)->queue(new SendMail(basicControl()->sender_email, $subject, $template));
                }
            }

            return back()->with('success', 'Email Sent Successfully');

        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    public function block(Request $request)
    {
        try {
            $user = Affiliate::where('id', $request->id)->firstOr(function () {
                throw new \Exception('No Affiliate found.');
            });

            $user->status = $user->status == 1 ? 0 : 1;
            $user->save();

            $message = $user->status == 1 ? 'Affiliate has been unblocked successfully.' : 'Affiliate has been blocked successfully.';

            return back()->with('success', $message);
        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    public function balanceUpdate(Request $request, $id){
        $request->validate([
            'amount' => 'required|numeric|min:1'
        ]);

        try {

            $user = Affiliate::where('id', $id)->firstOr(function () {
                throw new \Exception('User not found!');
            });
            $basic = basicControl();

            if ($request->balance_operation == 1) {

                $user->balance += $request->amount;
                $user->save();

                $transaction = new Transaction();
                $transaction->user_id = $user->id;
                $transaction->amount = getAmount($request->amount);
                $transaction->balance = getAmount($user->balance);
                $transaction->charge = 0;
                $transaction->trx_type = '+';
                $transaction->remarks = 'Add Balance to wallet';
                $transaction->save();

                $msg = [
                    'amount' => currencyPosition($transaction->amount),
                    'main_balance' => currencyPosition($user->balance),
                    'transaction' => $transaction->trx_id
                ];

                $action = [
                    "link" => 'affiliate.transaction',
                    "icon" => "fa fa-money-bill-alt text-white"
                ];
                $firebaseAction = 'affiliate.transaction';
                $this->userFirebasePushNotification($user, 'ADD_BALANCE', $msg, $firebaseAction);
                $this->userPushNotification($user, 'ADD_BALANCE', $msg, $action);
                $this->sendMailSms($user, 'ADD_BALANCE', $msg);

                return redirect()->route('admin.affiliate.profile.transaction', $user->id)->with('success', 'Balance Updated Successfully.');

            } else {

                if ($request->amount > $user->balance) {
                    return back()->with('error', 'Insufficient Balance to deducted.');
                }
                $user->balance -= $request->amount;
                $user->save();

                $transaction = new Transaction();
                $transaction->user_id = $user->id;
                $transaction->amount = getAmount($request->amount);
                $transaction->balance = $user->balance;
                $transaction->charge = 0;
                $transaction->trx_type = '-';
                $transaction->remarks = 'Deduction Balance from wallet';
                $transaction->save();

                $msg = [
                    'amount' => currencyPosition($transaction->amount),
                    'main_balance' => currencyPosition($user->balance),
                    'transaction' => $transaction->trx_id
                ];
                $action = [
                    "link" => route('affiliate.transaction'),
                    "icon" => "fa fa-money-bill-alt text-white"
                ];
                $firebaseAction = route('affiliate.transaction');
                $this->userFirebasePushNotification($user, 'DEDUCTED_BALANCE', $msg, $firebaseAction);
                $this->userPushNotification($user, 'DEDUCTED_BALANCE', $msg, $action);
                $this->sendMailSms($user, 'DEDUCTED_BALANCE', $msg);

                return redirect()->route('admin.affiliate.profile.transaction', $user->id)->with('success', 'Balance Updated Successfully.');

            }

        } catch (\Exception $exp) {
            return back()->with('error', $exp->getMessage());
        }
    }
    public function transaction(Request $request, $id){
        try {
            $data['affiliate'] = Affiliate::where('id', $id)->firstOr(function () {
                throw new \Exception('No affiliate data found.');
            });
            return view('admin.affiliate_management.transaction', compact('id'), $data);
        }catch (\Exception $e){
            return back()->with('error', $e->getMessage());
        }
    }

    public function transactionSearch(Request $request, $id)
    {
        $basicControl = basicControl();
        $search = $request->search['value'];

        $filterTransactionId = $request->filterTransactionID;
        $filterDate = explode('-', $request->filterDate);
        $startDate = $filterDate[0];
        $endDate = isset($filterDate[1]) ? trim($filterDate[1]) : null;

        $transaction = Transaction::with('affiliate')
            ->where('affiliate_id', $id)
            ->when(!empty($search), function ($query) use ($search) {
                $query->where(function ($innerQuery) use ($search) {
                    $innerQuery->where('trx_id', 'LIKE', "%{$search}%")
                        ->orWhere('remarks', 'LIKE', "%{$search}%");
                });
            })
            ->when(!empty($request->filterDate) && $endDate == null, function ($query) use ($startDate) {
                $startDate = Carbon::createFromFormat('d/m/Y', trim($startDate));
                $query->whereDate('created_at', $startDate);
            })
            ->when(!empty($request->filterDate) && $endDate != null, function ($query) use ($startDate, $endDate) {
                $startDate = Carbon::createFromFormat('d/m/Y', trim($startDate));
                $endDate = Carbon::createFromFormat('d/m/Y', trim($endDate));
                $query->whereBetween('created_at', [$startDate, $endDate]);
            })
            ->when(!empty($filterTransactionId), function ($query) use ($filterTransactionId) {
                return $query->where('trx_id', $filterTransactionId);
            })
            ->orderBy('id', 'DESC')
            ->get();

        return DataTables::of($transaction)
            ->addColumn('no', function () {
                static $counter = 0;
                $counter++;
                return $counter;
            })
            ->addColumn('trx', function ($item) {
                return $item->trx_id;
            })
            ->addColumn('amount', function ($item) {
                $statusClass = $item->trx_type == '+' ? 'text-success' : 'text-danger';
                return "<h6 class='mb-0 $statusClass '>" . $item->trx_type . ' ' . currencyPosition($item->amount) . "</h6>";
            })
            ->addColumn('charge', function ($item) {
                return currencyPosition($item->charge);

            })
            ->addColumn('remarks', function ($item) {
                return $item->remarks;
            })
            ->addColumn('date-time', function ($item) {
                return dateTime($item->created_at, 'd M Y h:i A');
            })
            ->rawColumns(['amount', 'charge'])
            ->make(true);
    }

    public function withdraw($id)
    {
        $data['affiliate'] = Affiliate::findOrFail($id);
        $data['methods'] = PayoutMethod::where('is_active', 1)->orderBy('id', 'asc')->get();
        return view('admin.affiliate_management.payout_log', $data);
    }

    public function withdrawSearch(Request $request, $id)
    {

        $filterTransactionId = $request->filterTransactionID;
        $filterStatus = $request->filterStatus;
        $filterMethod = $request->filterMethod;
        $basicControl = basicControl();
        $search = $request->search['value'];

        $filterDate = explode('-', $request->filterDate);
        $startDate = $filterDate[0];
        $endDate = isset($filterDate[1]) ? trim($filterDate[1]) : null;

        $payout = Payout::with('affiliate', 'method')->where('affiliate_id', $id)
            ->when(!empty($search), function ($query) use ($search) {
                return $query->where(function ($subquery) use ($search) {
                    $subquery->where('trx_id', 'LIKE', "%$search%")
                        ->orWhereHas('method', function ($q) use ($search) {
                            $q->where('name', 'LIKE', "%$search%");
                        });
                });
            })
            ->when(!empty($filterTransactionId), function ($query) use ($filterTransactionId) {
                return $query->where('trx_id', $filterTransactionId);
            })
            ->when(isset($filterStatus), function ($query) use ($filterStatus) {
                if ($filterStatus == "all") {
                    return $query->where('status', '!=', null);
                }
                return $query->where('status', $filterStatus);
            })
            ->when(isset($filterMethod), function ($query) use ($filterMethod) {
                return $query->whereHas('method', function ($subQuery) use ($filterMethod) {
                    if ($filterMethod == "all") {
                        $subQuery->where('id', '!=', null);
                    } else {
                        $subQuery->where('id', $filterMethod);
                    }
                });
            })
            ->when(!empty($request->filterDate) && $endDate == null, function ($query) use ($startDate) {
                $startDate = Carbon::createFromFormat('d/m/Y', trim($startDate));
                $query->whereDate('created_at', $startDate);
            })
            ->when(!empty($request->filterDate) && $endDate != null, function ($query) use ($startDate, $endDate) {
                $startDate = Carbon::createFromFormat('d/m/Y', trim($startDate));
                $endDate = Carbon::createFromFormat('d/m/Y', trim($endDate));
                $query->whereBetween('created_at', [$startDate, $endDate]);
            })
            ->get();


        return DataTables::of($payout)
            ->addColumn('No', function ($item) {
                static $counter = 0;
                $counter++;
                return $counter;
            })
            ->addColumn('trx', function ($item) {
                return $item->trx_id;
            })
            ->addColumn('method', function ($item) {
                return '<a class="d-flex align-items-center me-2 cursor-unset" href="javascript:void(0)">
                                <div class="flex-shrink-0">
                                  ' . $item->picture() . '
                                </div>
                                <div class="flex-grow-1 ms-3">
                                  <h5 class="text-hover-primary mb-0">' . optional($item->method)->name . '</h5>
                                </div>
                              </a>';
            })
            ->addColumn('amount', function ($item) {
                $statusClass = $item->getStatusClass();
                return "<h6 class='mb-0 $statusClass '>" . fractionNumber(getAmount($item->amount)) . ' ' . $item->payout_currency_code . "</h6>";

            })
            ->addColumn('charge', function ($item) {
                return "<span class='text-danger'>" . getAmount($item->charge) . ' ' . $item->payout_currency_code . "</span>";
            })
            ->addColumn('net amount', function ($item) {
                return "<h6>" . currencyPosition(getAmount($item->amount_in_base_currency)) . "</h6>";
            })
            ->addColumn('status', function ($item) {
                if ($item->status == 1) {
                    return '<span class="badge bg-soft-warning text-warning">' . trans('Pending') . '</span>';
                } else if ($item->status == 2) {
                    return '<span class="badge bg-soft-success text-success">' . trans('Successful') . '</span>';
                } else if ($item->status == 3) {
                    return '<span class="badge bg-soft-danger text-danger">' . trans('Cancel') . '</span>';
                }
            })
            ->addColumn('date', function ($item) {
                return dateTime($item->created_at, 'd M Y h:i A');
            })
            ->addColumn('action', function ($item) use ($basicControl) {

                $details = null;
                if ($item->information) {
                    $details = [];
                    foreach ($item->information as $k => $v) {
                        if ($v->type == "file") {
                            $details[kebab2Title($k)] = [
                                'type' => $v->type,
                                'field_name' => $v->field_name,
                                'field_value' => getFile(config('filesystems.default'), @$v->field_value ?? $v->field_name),
                            ];
                        } else {
                            $details[kebab2Title($k)] = [
                                'type' => $v->type,
                                'field_name' => $v->field_name,
                                'field_value' => @$v->field_value ?? $v->field_name
                            ];
                        }
                    }
                }

                $icon = $item->status == 1 ? 'pencil' : 'eye';

                $statusColor = '';
                $statusText = '';
                if ($item->status == 0) {
                    $statusColor = 'badge bg-soft-warning text-warning';
                    $statusText = 'Pending';
                } else if ($item->status == 1) {
                    $statusColor = 'badge bg-soft-warning text-warning';
                    $statusText = 'Pending';
                } else if ($item->status == 2) {
                    $statusColor = 'badge bg-soft-success text-success';
                    $statusText = 'Success';
                } else if ($item->status == 3) {
                    $statusColor = 'badge bg-soft-danger text-danger';
                    $statusText = 'Cancel';
                }

                return "<button type='button' class='btn btn-white btn-sm edit_btn'
                data-id='$item->id'
                data-info='" . json_encode($details) . "'
                data-sendername='" . $item->affiliate->firstname . ' ' . $item->affiliate->lastname . "'
                data-transactionid='$item->trx_id'
                data-feedback='$item->feedback'
                data-amount=' " . currencyPosition(getAmount($item->amount)) . "'
                data-method='" . optional($item->method)->name . "'
                data-gatewayimage='" . getFile(optional($item->method)->driver, optional($item->method)->image) . "'
                data-datepaid='" . dateTime($item->created_at, 'd M Y') . "'
                data-status='$item->status'

                data-status_color='$statusColor'
                data-status_text='$statusText'
                data-username='" . optional($item->affiliate)->username . "'
                data-action='" . route('admin.affiliate.payout.action', $item->id) . "'
                data-bs-toggle='modal'
                data-bs-target='#accountInvoiceReceiptModal'>  <i class='bi-$icon fill me-1'></i> </button>";
            })
            ->rawColumns(['method', 'amount', 'charge', 'net amount', 'status', 'action'])
            ->make(true);
    }

    public function earnings(Request $request, $affiliateId)
    {
        $startDate = $request->input('start_date', Carbon::now()->subDays(29)->startOfDay());
        $endDate = $request->input('end_date', Carbon::now()->endOfDay());

        $startDate = Carbon::parse($startDate);
        $endDate = Carbon::parse($endDate);

        $days = $startDate->diffInDays($endDate) + 1;

        $earningsData = Transaction::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('SUM(amount) as total')
        )
            ->where('affiliate_id', $affiliateId)
            ->where('transactional_type', AffiliateStatistics::class)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy(DB::raw('DATE(created_at)'))
            ->pluck('total', 'date');

        $labels = [];
        $amounts = [];

        for ($i = 0; $i < $days; $i++) {
            $date = $startDate->copy()->addDays($i);
            $dateKey = $date->format('Y-m-d');
            $labels[] = $date->format('M j, Y');
            $amounts[] = $earningsData[$dateKey] ?? 0;
        }

        $totalAmount = array_sum($amounts);
        $totalUnits = count($amounts);

        return response()->json([
            'labels' => $labels,
            'Price' => $amounts,
            'Unit' => $amounts,
            'TotalPriceInRange' => $totalAmount,
            'TotalUnitsInRange' => $totalUnits,
        ]);
    }
}
