<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BasicControl;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class AffiliateController extends Controller
{
    public function index(){
        return view('admin.control_panel.affiliate_control');
    }

    public function infoUpdate(Request $request){
        $validated = $request->validate([
            'affiliate_commission_percentage' => 'required|numeric|min:0|max:100',
            'affiliate_status' => 'required|string|in:0,1',
        ]);

        try {
            $basic = BasicControl();
            $response = BasicControl::updateOrCreate([
                'id' => $basic->id ?? ''
            ], [
                'affiliate_commission_percentage' => $request->affiliate_commission_percentage,
                'affiliate_status' => $request->affiliate_status,
            ]);

            if (!$response){
                throw new \Exception('Something went wrong, when updating data');
            }

            session()->flash('success', 'Affiliate Data has been successfully Updated');
            Artisan::call('optimize:clear');
            return back();
        }catch (\Exception $e){
            return back()->with('error', $e->getMessage());
        }
    }
}
