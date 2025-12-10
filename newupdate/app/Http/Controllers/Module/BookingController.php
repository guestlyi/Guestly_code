<?php

namespace App\Http\Controllers\Module;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Country;
use App\Models\Deposit;
use App\Models\Gateway;
use App\Models\Property;
use App\Models\Tax;
use App\Models\UserGateway;
use App\Traits\Notify;
use App\Traits\PaymentValidationCheck;
use App\Traits\Upload;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use function Symfony\Component\String\s;

class BookingController extends Controller
{
    use Notify, PaymentValidationCheck, Upload;
    public function bookingInfoStore(Request $request)
    {
        $request->validate([
            'datefilter'     => ['required', 'regex:/^\d{2}\/\d{2}\/\d{4} - \d{2}\/\d{2}\/\d{4}$/'],
            'property_id'    => ['required', 'exists:properties,id'],
            'adult_count'    => ['required', 'integer', 'min:1'],
            'children_count' => ['nullable', 'integer', 'min:0'],
            'pet_count'      => ['nullable', 'integer', 'min:0'],
        ]);

        try {
            $property = Property::with('pricing')->findOrFail($request->property_id);

            if ($property->host_id == auth()->id()){
                return response()->json([
                    'status' => false,
                    'message' => 'You are not allowed to book this property.',
                ]);
            }

            [$checkIn, $checkOut] = $this->parseDateRange($request->datefilter);
            $totalGuests = $request->adult_count + $request->children_count;
            $maxGuests = $property->features->max_guests ?? 0;

            if ($totalGuests > $maxGuests) {
                return back()->with('error' , 'Maximum number of guests reached.');
            }
            if ($checkIn->gte($checkOut)) {
                return back()->withErrors(['datefilter' => 'Check-in date must be before check-out date.']);
            }



            $days = $checkIn->diffInDays($checkOut);
            $totalInitialAmount = $this->calculateAmount($property, $days);

            $vendorTaxInfo = Tax::where('host_id', $property->host_id)->where('status', 1)->get();
            $totalTax = 0;
            foreach ($vendorTaxInfo as $tax) {
                if ($tax->type === 'percentage') {
                    $totalTax += ($totalInitialAmount * $tax->amount) / 100;
                } elseif ($tax->type === 'fixed') {
                    $totalTax += $tax->amount;
                }
            }

            $totalWithoutDiscount = $totalInitialAmount + ($property->pricing->cleaning_fee ?? 0) + ($property->pricing->service_fee ?? 0) + $totalTax;
            [$totalDiscount, $appliedDiscount] = $this->calculateDiscounts($property, $days, $totalInitialAmount);
            $finalAmount = max(0, $totalWithoutDiscount - $totalDiscount);

            $site_charge = 0;
            $host_received = 0;

            if ($finalAmount > 0) {
                $booking_charge_percentage = basicControl()->booking_charge;

                $booking_charge_percentage = min(max($booking_charge_percentage, 0), 100);

                $site_charge = round(($finalAmount * $booking_charge_percentage) / 100, 2);
                $host_received = round($finalAmount - $site_charge, 2);
            }

            $booking = Booking::create([
                'property_id'            => $property->id,
                'guest_id'               => auth()->id(),
                'check_in_date'          => $checkIn,
                'check_out_date'         => $checkOut,
                'information'            => [
                    'adults'   => $request->adult_count,
                    'children' => $request->children_count,
                    'pets'     => $request->pet_count,
                ],
                'applied_discount'       => $appliedDiscount,
                'amount_without_discount'=> $totalWithoutDiscount,
                'discount_amount'        => $totalDiscount,
                'total_amount'           => $finalAmount,
                'site_charge'           => $site_charge,
                'host_received'           => $host_received,
                'status'                 => 0,
            ]);

            return response()->json([
                'status' => 'success',
                'booking' => $booking,
            ]);
        } catch (\Exception $e) {
            return back()->withErrors($e->getMessage());
        }
    }

    public function bookingGuestInfo($uid)
    {
        try {
            $data['booking'] = Booking::with('property.pricing', 'property.features')
                ->where('uid', $uid)
                ->where('guest_id', auth()->id())
                ->firstOr(function () {
                    throw new \Exception('Booking cannot be found.');
                });

            $data['countries'] = config('country');

            $existingGuests = auth()->user()->allRelatives;

            $adultRelatives = $existingGuests->relatives['adult'] ?? [];
            $childRelatives = $existingGuests->relatives['children'] ?? [];

            foreach ($adultRelatives as &$guest) {
                if (isset($guest['image']['path'])){
                    $guest['image']['imageUrl'] = getFile($guest['image']['driver'], $guest['image']['path']);
                }else{
                    $guest['image']['imageUrl'] = null;
                }
            }
            unset($guest);

            foreach ($childRelatives as &$guest) {
                if (isset($guest['image']['path'])){
                    $guest['image']['imageUrl'] = getFile($guest['image']['driver'], $guest['image']['path']);
                }else{
                    $guest['image']['imageUrl'] = null;
                }
            }
            unset($guest);

            $data['adultRelatives'] = $adultRelatives;
            $data['childRelatives'] = $childRelatives;


            return view(template().'frontend.services.payment.guest_info_form', $data);
        }catch (\Exception $e) {
            return back()->withErrors($e->getMessage());
        }
    }
    public function bookingPaymentInfo($uid)
    {

        try {
            $booking = Booking::with('property.pricing', 'property.features', 'property.reviewSummary')
                ->where('uid', $uid)
                ->where('guest_id', auth()->id())
                ->firstOr(function () {
                    throw new \Exception('Booking cannot be found.');
                });

            $property = $booking->property;
            $gateways = Gateway::where('status', 1)->orderBy('sort_by', 'asc')->get();

            return view(template().'frontend.services.payment.payment_form', compact('booking', 'gateways', 'property'));
        }catch (\Exception $e) {
            return back()->withErrors($e->getMessage());
        }
    }
    public function bookingUpdate(Request $request, $uid)
    {
        $booking = Booking::with(['property.features', 'property.pricing'])->where('uid', $uid)->firstOrFail();

        $totalGuests = $request->adults + $request->children;
        $maxGuests = $booking->property->features->max_guests ?? 0;

        if ($totalGuests > $maxGuests) {
            return response()->json(['error' => 'Maximum number of guests reached.'], 422);
        }

        $checkIn = Carbon::parse($request->check_in_date);
        $checkOut = Carbon::parse($request->check_out_date);
        $days = $checkIn->diffInDays($checkOut);

        $totalInitialAmount = $this->calculateAmount($booking->property, $days);
        $totalWithoutDiscount = $totalInitialAmount + ($booking->property->pricing->cleaning_fee ?? 0) + ($booking->property->pricing->service_fee ?? 0);
        [$totalDiscount, $appliedDiscount] = $this->calculateDiscounts($booking->property, $days, $totalInitialAmount);
        $finalAmount = max(0, $totalWithoutDiscount - $totalDiscount);

        $booking->check_in_date = $checkIn;
        $booking->check_out_date = $checkOut;
        $booking->information = [
            'adults'   => $request->adults,
            'children' => $request->children,
            'pets'     => $request->pets,
        ];
        $booking->applied_discount = $appliedDiscount;
        $booking->amount_without_discount = $totalWithoutDiscount;
        $booking->discount_amount = $totalDiscount;
        $booking->total_amount = $finalAmount;
        $booking->save();


        return response()->json([
            'success' => true,
            'booking' => [
                'check_in_date' => dateTime($booking->check_in_date),
                'check_out_date' => dateTime($booking->check_out_date),
                'adults' => $booking->information['adults'],
                'children' => $booking->information['children'],
                'pets' => $booking->information['pets'],
                'total_amount' => $booking->total_amount,
                'nights' => $days,
                'total_amount_without_discount' => $booking->amount_without_discount,
            ]
        ]);
    }
    private function parseDateRange($dateRange)
    {
        [$start, $end] = explode(' - ', $dateRange);
        return [
            Carbon::createFromFormat('d/m/Y', trim($start)),
            Carbon::createFromFormat('d/m/Y', trim($end))
        ];
    }

    private function calculateAmount($property, $days)
    {
        $pricing = $property->pricing;
        $amount = 0;

        if ($days < 7) {
            $amount = $pricing->nightly_rate * $days;
        } elseif ($days < 30) {
            $weeks = floor($days / 7);
            $days %= 7;
            $amount = ($pricing->weekly_rate * $weeks) + ($pricing->nightly_rate * $days);
        } else {
            $months = floor($days / 30);
            $days %= 30;

            $amount = $pricing->monthly_rate * $months;

            if ($days >= 7) {
                $weeks = floor($days / 7);
                $days %= 7;
                $amount += ($pricing->weekly_rate * $weeks);
            }

            $amount += $pricing->nightly_rate * $days;
        }

        return $amount;
    }

    private function calculateDiscounts($property, $days, $baseAmount)
    {
        $discounts = is_string($property->discount_info)
            ? json_decode($property->discount_info, true)
            : $property->discount_info;

        $totalDiscount = 0;
        $applied = [];

        if (!is_array($discounts)) return [0, []];

        if (($discounts['new_listing']['enabled'] ?? '') === 'on') {
            $totalDiscount += ($baseAmount * floatval($discounts['new_listing']['percent'] ?? 0)) / 100;
            $applied[] = 'new_listing';
        }

        if ($days >= 7 && $days < 30 && ($discounts['weekly']['enabled'] ?? '') === 'on') {
            $totalDiscount += ($baseAmount * floatval($discounts['weekly']['percent'] ?? 0)) / 100;
            $applied[] = 'weekly';
        }

        if ($days >= 30 && ($discounts['monthly']['enabled'] ?? '') === 'on') {
            $totalDiscount += ($baseAmount * floatval($discounts['monthly']['percent'] ?? 0)) / 100;
            $applied[] = 'monthly';
        }

        if (!empty($discounts['others'])) {
            foreach ($discounts['others'] as $index => $other) {
                if (($other['enabled'] ?? '') === 'on') {
                    $totalDiscount += ($baseAmount * floatval($other['percent'] ?? 0)) / 100;
                    $applied[] = $other['name'] ?? "other_{$index}";
                }
            }
        }

        return [$totalDiscount, $applied];
    }

    public function bookingUserInfoUpdate(Request $request, $uid)
    {
        $request->validate([
            'adults' => 'nullable|array',
            'adults.*.first_name' => 'required|string|max:100',
            'adults.*.last_name' => 'required|string|max:100',
            'adults.*.gender' => 'required|in:male,female',
            'adults.*.birth_date' => 'required|date|date_format:Y-m-d|before:today',
            'adults.*.country' => 'nullable|string|max:100',
            'adults.*.email' => 'nullable|email|max:255',
            'adults.*.phone_code' => 'nullable|string|max:20',
            'adults.*.phone' => 'nullable|string|max:20',
            'adults.*.select_list' => 'required',
            'adults.*.save_for_future' => 'nullable|in:0,1',

            'children' => 'nullable|array',
            'children.*.first_name' => 'required|string|max:100',
            'children.*.last_name' => 'required|string|max:100',
            'children.*.gender' => 'required|in:male,female',
            'children.*.birth_date' => 'required|date|date_format:Y-m-d|before:today',
            'children.*.country' => 'nullable|string|max:100',
            'children.*.select_list' => 'required',
            'children.*.save_for_future' => 'nullable|in:0,1',
        ]);

        $existingRelatives = auth()->user()->allRelatives->relatives ?? ['adult' => [], 'children' => []];

        $mapRelatives = fn($list) => collect($list)->keyBy('serial')->all();
        $adultMap = $mapRelatives($existingRelatives['adult'] ?? []);
        $childMap = $mapRelatives($existingRelatives['children'] ?? []);

        $processGuests = function ($guests, $type, $onlyNew = false) use ($request, $adultMap, $childMap) {
            $results = [];

            foreach ($guests as $index => $guest) {
                $isNew = ($guest['select_list'] ?? '') === 'new';
                $savePermitted = ($guest['save_for_future'] ?? '0') === '1';

                if ($onlyNew && (!$isNew || !$savePermitted)) {
                    continue;
                }

                $photoKey = "{$type}.$index.photo";
                $image = $driver = null;

                if ($request->hasFile($photoKey)) {
                    $upload = $this->fileUpload(
                        $request->file($photoKey),
                        config('filelocation.booking.path'),
                        null,
                        null,
                        'webp',
                        60
                    );
                    $image = $upload['path'] ?? null;
                    $driver = $upload['driver'] ?? null;
                } elseif (!$isNew) {
                    $serial = (int) $guest['select_list'];
                    $existing = $type === 'adults' ? $adultMap[$serial] ?? null : $childMap[$serial] ?? null;
                    if ($existing && isset($existing['image'])) {
                        $image = $existing['image']['path'] ?? null;
                        $driver = $existing['image']['driver'] ?? null;
                    }
                }

                $data = [
                    'firstname' => $guest['first_name'],
                    'lastname' => $guest['last_name'],
                    'gender' => $guest['gender'],
                    'birth_date' => $guest['birth_date'],
                    'country' => $guest['country'] ?? null,
                    'image' => $image ? ['driver' => $driver, 'path' => $image] : null,
                ];

                if ($type === 'adults') {
                    $data['email'] = $guest['email'] ?? null;
                    $data['phone'] = $guest['phone'] ?? null;
                    $data['phone_code'] = $guest['phone_code'] ?? null;
                }

                $results[] = $data;
            }

            return $results;
        };

        $for_booking = [
            'adult' => $processGuests($request->input('adults', []), 'adults'),
            'children' => $processGuests($request->input('children', []), 'children'),
        ];

        $for_update = [
            'adult' => $processGuests($request->input('adults', []), 'adults', true),
            'children' => $processGuests($request->input('children', []), 'children', true),
        ];

        $mergeWithSerial = function (array $existing, array $newItems) {
            $serial = collect($existing)->pluck('serial')->max() ?? 0;

            foreach ($newItems as $item) {
                $serial++;
                $item['serial'] = $serial;
                $existing[] = $item;
            }

            return $existing;
        };

        $updatedRelatives = [
            'adult' => $mergeWithSerial($existingRelatives['adult'] ?? [], $for_update['adult']),
            'children' => $mergeWithSerial($existingRelatives['children'] ?? [], $for_update['children']),
        ];

        auth()->user()->allRelatives()->updateOrCreate(
            ['user_id' => auth()->id()],
            ['relatives' => $updatedRelatives]
        );

        $booking = Booking::where('uid', $uid)->firstOrFail();
        $booking->user_info = $for_booking;
        $booking->save();

        return response()->json([
            'status' => 'success',
            'booking' => $booking,
        ]);
    }



    public function bookingPayment(Request $request)
    {
        try {
            $booking = Booking::with('property')->where('uid', $request->booking_uid)->first();

            if (!$booking) {
                throw new \Exception('Booking could not be found');
            }

            if (empty($booking->user_info)) {
                throw new \Exception('Booking user information is required');
            }

            $amount = $request->amount;
            $gateway = $request->payout_method_id;
            $currency = $request->supported_currency ?? $request->base_currency;
            $cryptoCurrency = $request->supported_crypto_currency;

            $checkAmount = $this->checkAmountValidate($amount, $currency, $gateway, $cryptoCurrency, 'yes');

            if ($checkAmount['status'] == false) {
                return back()->with('error', $checkAmount['message']);
            }

            $deposit = Deposit::create([
                'user_id' => Auth::user()->id,
                'depositable_type' => Booking::class,
                'depositable_id' => $booking->id,
                'host_id' => $booking->property->host_id ?? null,
                'payment_method_id' => $checkAmount['gateway_id'],
                'payment_method_currency' => $checkAmount['currency'],
                'amount' => $checkAmount['amount'],
                'percentage_charge' => $checkAmount['percentage_charge'],
                'fixed_charge' => $checkAmount['fixed_charge'],
                'payable_amount' => $checkAmount['payable_amount'],
                'base_currency_charge' => $checkAmount['charge_baseCurrency'],
                'payable_amount_in_base_currency' => $checkAmount['payable_amount_baseCurrency'],
                'status' => 0,
            ]);

            return redirect(route('payment.process', $deposit->trx_id));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
