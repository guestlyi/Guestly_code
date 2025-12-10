<?php

namespace App\Http\Controllers\Affiliate;

use App\Http\Controllers\Controller;
use App\Models\Kyc;
use App\Models\UserKyc;
use App\Traits\Upload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class KycVerificationController extends Controller
{
    use Upload;

    public function kyc()
    {
        $data['kyc'] = Kyc::where('status', 1)->where('apply_for', 1)->first();
        $data['userKyc'] = UserKyc::where('affiliate_id', auth('affiliate')->id())->where('kyc_id', $data['kyc']->id)->orderBy('id', 'desc')->first();
        return view(template() . 'affiliate.verification_center.form', $data);
    }

    public function verificationSubmit(Request $request)
    {
        $kyc = Kyc::where('id', $request->type)->where('status', 1)->firstOrFail();

        $params = $kyc->input_form;
        $reqData = $request->except('_token', '_method');
        $rules = [];
        if ($params !== null) {
            foreach ($params as $key => $cus) {
                $rules[$key] = [$cus->validation == 'required' ? $cus->validation : 'nullable'];
                if ($cus->type === 'file') {
                    $rules[$key][] = 'image';
                    $rules[$key][] = 'mimes:jpeg,jpg,png';
                    $rules[$key][] = 'max:2048';
                } elseif ($cus->type === 'text') {
                    $rules[$key][] = 'max:191';
                } elseif ($cus->type === 'number') {
                    $rules[$key][] = 'numeric';
                } elseif ($cus->type === 'textarea') {
                    $rules[$key][] = 'min:3';
                    $rules[$key][] = 'max:300';
                }
            }
        }


        $params = $kyc->input_form;
        $validator = Validator::make($reqData, $rules);
        if ($validator->fails()) {
            $validator->errors()->add('kyc', 'Your unique error message for the kyc field');
            return back()->withErrors($validator)->withInput();
        }

        $reqField = [];
        foreach ($request->except('_token', '_method', 'type') as $k => $v) {
            foreach ($params as $inKey => $inVal) {
                if ($k == $inKey) {
                    if ($inVal->type == 'file' && $request->hasFile($inKey)) {
                        try {
                            $file = $this->fileUpload($request[$inKey], config('filelocation.kyc.path'), null, config('filelocation.kyc.size'), 'webp', 80);
                            $reqField[$inKey] = [
                                'field_name' => $inVal->field_name,
                                'field_label' => $inVal->field_label,
                                'field_value' => $file['path'],
                                'field_driver' => $file['driver'],
                                'validation' => $inVal->validation,
                                'type' => $inVal->type,
                            ];
                        } catch (\Exception $exp) {
                            session()->flash('error', 'Could not upload your ' . $inKey);
                            return back()->withInput();
                        }
                    } else {
                        $reqField[$inKey] = [
                            'field_name' => $inVal->field_name,
                            'field_label' => $inVal->field_label,
                            'validation' => $inVal->validation,
                            'field_value' => $v,
                            'type' => $inVal->type,
                        ];
                    }
                }
            }
        }

        $userKyc = new UserKyc();
        $userKyc->affiliate_id = auth('affiliate')->id();
        $userKyc->kyc_id = $kyc->id;
        $userKyc->kyc_type = $kyc->name;
        $userKyc->kyc_info = $reqField;
        $userKyc->apply_for = 1;
        $userKyc->status = $kyc->is_automatic == 1 ? 1 : 0;
        $userKyc->save();

        $userKyc->affiliate->identity_verify = 1;
        $userKyc->affiliate->save();

        if ($kyc->is_automatic == 1) {
            if ($userKyc->affiliate){
                $userKyc->affiliate->is_affiliatable = 1;
                $userKyc->affiliate->identity_verify = 2;
                $userKyc->affiliate->save();
            }
        }

        return back()->with('success', 'KYC Sent Successfully');
    }
}
