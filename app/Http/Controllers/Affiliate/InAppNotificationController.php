<?php

namespace App\Http\Controllers\Affiliate;

use App\Events\UpdateAdminNotification;
use App\Events\UpdateAffiliateNotification;
use App\Events\UpdateUserNotification;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Affiliate;
use App\Models\InAppNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InAppNotificationController extends Controller
{
    public function showByAdmin()
    {
        $siteNotifications = InAppNotification::whereHasMorph(
            'inAppNotificationable',
            [Admin::class],
            function ($query) {
                $query->where([
                    'in_app_notificationable_id' => Auth::id()
                ]);
            }
        )->latest()->get();

        return $siteNotifications;
    }

    public function show()
    {
        $siteNotifications = InAppNotification::whereHasMorph(
            'inAppNotificationable',
            [Affiliate::class],
            function ($query) {
                $query->where([
                    'in_app_notificationable_id' => auth()->guard('affiliate')->id()
                ]);
            }
        )->latest()->get();
        return $siteNotifications;
    }

    public function readAt($id)
    {
        $siteNotification = InAppNotification::find($id);
        if ($siteNotification) {
            $siteNotification->delete();
            if (Auth::guard('admin')->check()) {
                event(new UpdateAdminNotification(Auth::id()));
            }
            else {
                event(new UpdateUserNotification(auth()->guard('affiliate')->id(),'affiliate'));
            }
            $data['status'] = true;
        } else {
            $data['status'] = false;
        }
        return $data;
    }

    public function readAllByAdmin()
    {
        $siteNotification = InAppNotification::whereHasMorph(
            'inAppNotificationable',
            [Admin::class],
            function ($query) {
                $query->where([
                    'in_app_notificationable_id' => Auth::id()
                ]);
            }
        )->delete();

        if ($siteNotification) {
            event(new UpdateAdminNotification(Auth::id()));
        }
        $data['status'] = true;
        return $data;
    }

    public function readAll()
    {
        $siteNotification = InAppNotification::whereHasMorph(
            'inAppNotificationable',
            [Affiliate::class],
            function ($query) {
                $query->where([
                    'in_app_notificationable_id' => auth()->guard('affiliate')->id()
                ]);
            }
        )->delete();
        if ($siteNotification) {
            event(new UpdateUserNotification(auth()->guard('affiliate')->id(),'affiliate'));
        }

        $data['status'] = true;
        return $data;
    }
}
