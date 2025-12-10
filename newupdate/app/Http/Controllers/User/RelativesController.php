<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\UserRelative;
use App\Traits\Notify;
use App\Traits\Upload;
use Illuminate\Http\Request;

class RelativesController extends Controller
{
    use Upload, Notify;
    public function relatives(Request $request)
    {
        $relativeData = UserRelative::where('user_id', auth()->id())->first();
        $relatives = [];

        $searchName = trim(strtolower($request->name));

        foreach ($relativeData->relatives['adult'] ?? [] as $adult) {
            $fullName = strtolower(trim($adult['firstname'] . ' ' . $adult['lastname']));
            if ($searchName === '' || stripos($fullName, $searchName) !== false) {
                $adult['type'] = 'adult';
                $relatives[] = $adult;
            }
        }

        foreach ($relativeData->relatives['children'] ?? [] as $child) {
            $fullName = strtolower(trim($child['firstname'] . ' ' . $child['lastname']));
            if ($searchName === '' || stripos($fullName, $searchName) !== false) {
                $child['type'] = 'children';
                $relatives[] = $child;
            }
        }

        return view(template().'user.profile.relative.relatives', compact('relatives'));
    }

    public function relativeEdit ($type, $serial)
    {
        try {
            $relativeData = UserRelative::where('user_id', auth()->id())->first();
            $allRelatives = $relativeData->relatives[$type] ?? [];

            $editData = collect($allRelatives)->firstWhere('serial', (int)$serial);
            if (!$editData) {
                return back()->with('error', 'Relative not found');
            }

            $countries = Country::where('status', 1)->get();

            return view(template().'user.profile.relative.edit', compact('editData','countries', 'serial', 'type'));
        }catch (\Exception $exception){
            return back()->with('error', $exception->getMessage());
        }
    }

    public function relativeUpdate(Request $request)
    {
        $request->validate([
            'type' => 'required|in:adult,children',
            'serial' => 'required|integer',
            'firstname' => 'required|string|max:100',
            'lastname' => 'required|string|max:100',
            'gender' => 'required|in:male,female',
            'country' => 'required|string|max:100',
            'birth_date' => 'required|string|max:100',
            'email' => 'nullable|email|max:255',
            'phone_code' => 'nullable|string|max:20',
            'phone' => 'nullable|string|max:20',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        try {
            $relativeData = UserRelative::where('user_id', auth()->id())->firstOr(function (){
                throw new \Exception('Relative not found');
            });
            $type = $request->type;
            $serial = (int) $request->serial;

            $allRelatives = $relativeData->relatives[$type] ?? [];

            $index = collect($allRelatives)->search(fn($item) => $item['serial'] == $serial);

            if ($index === false) {
                return back()->with('error', 'Relative not found');
            }
            $oldData = $allRelatives[$index];

            if ($request->hasFile('photo')) {
                $upload = $this->fileUpload(
                    $request->file('photo'),
                    config('filelocation.booking.path'),
                    null,
                    null,
                    'webp',
                    60
                );

                $image = [
                    'path' => $upload['path'] ?? null,
                    'driver' => $upload['driver'] ?? null,
                ];
            } else {
                $image = $oldData['image'] ?? null;
            }

            $updated = [
                'serial' => $serial,
                'firstname' => $request->firstname,
                'lastname' => $request->lastname,
                'gender' => $request->gender,
                'birth_date' => $request->birth_date ?? null,
                'country' => $request->country,
                'email' => $request->email,
                'phone' => $request->phone,
                'phone_code' => $request->phone_code,
                'image' => $image,
            ];

            $allRelatives[$index] = $updated;
            $relatives = $relativeData->relatives;
            $relatives[$type] = $allRelatives;
            $relativeData->relatives = $relatives;
            $relativeData->save();

            return back()->with('success', 'Relative updated successfully.');

        } catch (\Exception $e) {
            return back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function relativeDelete(Request $request)
    {
        $request->validate([
            'type' => 'required|in:adult,children',
            'serial' => 'required|integer',
        ]);

        try {
            $relativeData = UserRelative::where('user_id', auth()->id())->firstOr(function () {
                throw new \Exception('Relative not found');
            });

            $type = $request->type;
            $serial = (int)$request->serial;

            $allRelatives = $relativeData->relatives[$type] ?? [];

            $relativeToDelete = collect($allRelatives)->firstWhere('serial', $serial);

            if (!$relativeToDelete) {
                return back()->with('error', 'Relative not found.');
            }

            if (!empty($relativeToDelete['image']['path']) && !empty($relativeToDelete['image']['driver'])) {
                $this->fileDelete($relativeToDelete['image']['driver'], $relativeToDelete['image']['path']);
            }

            $updatedRelatives = collect($allRelatives)
                ->reject(fn($item) => $item['serial'] == $serial)
                ->values()
                ->toArray();

            $relatives = $relativeData->relatives;
            $relatives[$type] = $updatedRelatives;
            $relativeData->relatives = $relatives;
            $relativeData->save();

            return back()->with('success', 'Relative deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function relativeAdd ()
    {
        $countries = Country::where('status', 1)->get();
        return view(template().'user.profile.relative.add', compact('countries'));
    }

    public function relativeStore(Request $request)
    {
        $request->validate([
            'type' => 'required|in:adult,children',
            'firstname' => 'required|string|max:100',
            'lastname' => 'required|string|max:100',
            'gender' => 'required|in:male,female',
            'country' => 'required|string|max:100',
            'email' => 'nullable|email|max:255',
            'phone_code' => 'nullable|string|max:20',
            'phone' => 'nullable|string|max:20',
            'birth_date' => 'nullable|date|before:today',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        try {
            $relativeData = UserRelative::firstOrNew(['user_id' => auth()->id()]);

            $type = $request->type;

            $allRelatives = $relativeData->relatives[$type] ?? [];

            $maxSerial = collect($allRelatives)->max('serial') ?? 0;
            $newSerial = $maxSerial + 1;

            $image = null;
            if ($request->hasFile('photo')) {
                $upload = $this->fileUpload(
                    $request->file('photo'),
                    config('filelocation.booking.path'),
                    null,
                    null,
                    'webp',
                    60
                );

                $image = [
                    'path' => $upload['path'] ?? null,
                    'driver' => $upload['driver'] ?? null,
                ];
            }

            $newRelative = [
                'serial' => $newSerial,
                'firstname' => $request->firstname,
                'lastname' => $request->lastname,
                'gender' => $request->gender,
                'birth_date' => $request->birth_date ?? null,
                'country' => $request->country,
                'image' => $image,
            ];

            if ($type === 'adult') {
                $newRelative['email'] = $request->email;
                $newRelative['phone'] = $request->phone;
                $newRelative['phone_code'] = $request->phone_code;
            }

            $allRelatives[] = $newRelative;
            $relatives = $relativeData->relatives ?? [];
            $relatives[$type] = $allRelatives;

            $relativeData->user_id = auth()->id();
            $relativeData->relatives = $relatives;
            $relativeData->save();

            return back()->with('success', 'Relative added successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }
}
