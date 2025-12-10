<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Pricing;
use App\Models\User;
use App\Models\VendorInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HostingController extends Controller
{
    public function enterHome()
    {
        $data['minimum_price'] = Pricing::min('nightly_rate');
        $data['hosts'] = User::select('users.*')
            ->join('vendor_infos', 'users.id', '=', 'vendor_infos.vendor_id')
            ->where('users.role', 1)
            ->orderByDesc('vendor_infos.avg_rating')
            ->with('vendorInfo')
            ->take(4)
            ->get();

        $data['googleMapApiKey'] = basicControl()->google_map_app_key;
        $data['googleMapId'] = basicControl()->google_map_id;

        return view(template().'vendor.hosting.enter_home', $data);
    }
    public function introduction()
    {
        if (auth()->user()->role != 1) {
            DB::transaction(function () {
                $user = Auth::user();
                $user->role = 1;
                $user->save();

                $vendorInfo = new VendorInfo();
                $vendorInfo->vendor_id = auth()->id();
                $vendorInfo->avg_rating = 0.00;
                $vendorInfo->save();
            });
        }

        return view(template().'vendor.listing.listIntroduction');
    }
}
