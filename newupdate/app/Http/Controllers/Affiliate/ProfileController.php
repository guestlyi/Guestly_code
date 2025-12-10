<?php

namespace App\Http\Controllers\Affiliate;

use App\Http\Controllers\Controller;
use App\Traits\Notify;
use App\Traits\Upload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ProfileController extends Controller
{
    use Upload, Notify;
    public function imageUpdate(Request $request){
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

        $user = auth('affiliate')->user();

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

    public function basicProfileUpdate(Request $request)
    {
        $request->validate([
            'type' => 'required|string',
            'value' => 'nullable|string',
        ]);
        $user = auth('affiliate')->user();
        if (in_array($request->type, ['Username', 'Email'])) {
            $request->validate([
                'value' => [
                    'nullable',
                    'string',
                    Rule::unique('affiliates', strtolower($request->type))->ignore($user->id)
                ],
            ]);
        }

        $user->{$request->type} = $request->value;
        $user->save();

        return response()->json(['success' => true]);
    }

    public function basicPhoneUpdate(Request $request)
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

    public function passwordChange(Request $request){

        return view(template().'affiliate.profile.password_change');
    }

    public function passwordUpdate(Request $request){
        $request->validate([
            'current_password'      => 'required|string',
            'new_password'          => ['required', 'string', 'min:8', 'different:current_password'],
            'confirm_password'   => 'required|same:new_password',
        ]);

        try {
            $affiliate = auth('affiliate')->user();

            if (!Hash::check($request->current_password, $affiliate->password)) {
                return back()->with('error', 'Current password does not match.');
            }

            if (basicControl()->strong_password) {
                $passwordRules = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*[\d\s\W]).{8,}$/';
                if (!preg_match($passwordRules, $request->new_password)) {
                    return back()->with('error', 'Password must include uppercase, lowercase, number/symbol, and be at least 8 characters.');
                }
            }

            $affiliate->password = Hash::make($request->new_password);
            $affiliate->save();

            return back()->with('success', 'Password updated successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }
}
