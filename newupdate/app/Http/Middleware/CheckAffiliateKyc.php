<?php

namespace App\Http\Middleware;

use App\Models\Kyc as KYCModel;
use App\Models\UserKyc;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckAffiliateKyc
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        if(auth('affiliate')->check() && config('services.sumsub.status') && auth('affiliate')->user()->identity_verify != 2) {
            return redirect()->route('affiliate.verification.kyc')->with('error', 'Please submit KYC information to access all the resource');
        }

        if(auth('affiliate')->check() && auth('affiliate')->user()->identity_verify != 2) {
            return redirect()->route('affiliate.verification.kyc')->with('error', 'Please submit KYC information to access all the resource');
        }

        $kycTypes = KYCModel::where('status', 1)->where('apply_for', 1)->pluck('id');
        if(count($kycTypes) > 0){
            $userKyc = UserKyc::where('affiliate_id', auth('affiliate')->user()->id)->whereIn('kyc_id', $kycTypes)->get();
            $userKycIds = $userKyc->pluck('kyc_id')->toArray();
            $missingKycTypes = array_diff($kycTypes->toArray(), $userKycIds);

            if (!empty($missingKycTypes)) {
                return redirect()->route('affiliate.verification.kyc')->with('error', 'Please submit KYC information to access all the resource');
            }

            $statuses = $userKyc->pluck('status');
            if (!in_array(1, $statuses->toArray())) {
                return redirect()->route('affiliate.verification.kyc')->with('error', 'Your KYC is not approved yet');
            }
        }

        return $next($request);
    }
}
