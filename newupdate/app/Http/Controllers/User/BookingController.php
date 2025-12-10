<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Mail\BookingInvoiceMail;
use App\Models\Booking;
use App\Services\BasicService;
use App\Traits\Notify;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class BookingController extends Controller
{
    use Notify;
    public function confirm(Request $request)
    {
        $validated = $request->validate([
            'booking_uid' => 'required|exists:bookings,uid',
            'agree' => 'required|in:0,1',
        ]);

        try {
            $booking = Booking::with('guest','property.host')->where('uid', $validated['booking_uid'])->firstOr(function () {
                throw new \Exception('BookingUid not found');
            });

            $isAccepted = $validated['agree'] == 1;
            $booking->status = $isAccepted ? 1 : 2;
            $booking->save();

            (new BasicService())->distributeAffiliate($booking);

            $statusKey = $isAccepted ? 'PROPERTY_BOOKING_ACCEPTED' : 'PROPERTY_BOOKING_REJECTED';
            $message = $isAccepted
                ? 'Your booking has been confirmed.'
                : 'Your booking has been rejected.';

            $params = [
                'title' => $booking->property->title,
                'date_range' => "{$booking->check_in_date}-{$booking->check_out_date}",
                'guest_name' => "{$booking->guest->firstname} {$booking->guest->lastname}",
            ];

            $action = [
                'link' => route('user.reservations'),
                'icon' => 'fa fa-money-bill-alt text-white',
            ];

            $this->sendMailSms($booking->guest, $statusKey, $params);
            $this->userPushNotification($booking->guest, $statusKey, $params, $action);
            $this->userFirebasePushNotification($booking->guest, $statusKey, $params);

            $booking->logo = getFile(basicControl()->logo_driver, basicControl()->logo);
            $booking->favicon = getFile(basicControl()->favicon_driver, basicControl()->favicon);
            $booking->base_currency = basicControl()->base_currency;
            Mail::to($booking->guest->email)->send(new BookingInvoiceMail($booking));

            return back()->with('success', $message);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
    public function completed(Request $request){
        $validated = $request->validate([
            'booking_uid' => 'required|exists:bookings,uid',
        ]);
        try {
            $booking = Booking::with('guest','property.host')->where('uid', $validated['booking_uid'])->firstOr(function () {
                throw new \Exception('BookingUid not found');
            });
            $booking->status = 3;
            $booking->save();

            return back()->with('success', 'Booking has been marked as completed successfully.');
        }catch (\Exception $e){
            return back()->with('error', $e->getMessage());
        }
    }

    public function refunded(Request $request){
        $validated = $request->validate([
            'booking_uid' => 'required|exists:bookings,uid',
        ]);
        try {
            $booking = Booking::with('guest','property.host','property.pricing')->where('uid', $validated['booking_uid'])->firstOr(function () {
                throw new \Exception('BookingUid not found');
            });

            $booking->status = 5;
            $booking->save();

            return back()->with('success', 'Booking has been marked as refunded successfully.');
        }catch (\Exception $e){
            return back()->with('error', $e->getMessage());
        }
    }
}
