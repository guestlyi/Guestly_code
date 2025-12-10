<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Amenity;
use App\Models\Availability;
use App\Models\City;
use App\Models\Content;
use App\Models\Country;
use App\Models\Destination;
use App\Models\GoogleMapApi;
use App\Models\Pricing;
use App\Models\Property;
use App\Models\PropertyAmenity;
use App\Models\PropertyCategory;
use App\Models\PropertyFeature;
use App\Models\PropertyIcalSource;
use App\Models\PropertyPhotos;
use App\Models\PropertyStyle;
use App\Models\PropertyType;
use App\Models\State;
use App\Models\VendorInfo;
use App\Services\GeminiService;
use App\Services\OpenAiService;
use App\Traits\Notify;
use App\Traits\Upload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Calculation\Category;

class PropertyController extends Controller
{
    use Upload, Notify;

    public function introduction()
    {
        return view(template().'vendor.listing.listIntroduction');
    }
    public function introductionGuide()
    {
        $data['content'] = Content::with('contentDetails')->where('name','property_list_introduction')->first();
        return view(template().'vendor.listing.introduction', $data);
    }
    public function aboutYourPlace(Request $request)
    {
        if ($request->property_id){
            $data['property'] = Property::where('id', $request->property_id)->where('host_id', Auth::id())->firstOr(function () {
                throw new \Exception('Property not found.');
            });
        }

        $data['currentStep'] = 0;
        $data['totalSteps'] = 8;
        $data['phase'] = 'phase 1';
        return view(template().'vendor.listing.about_your_place', $data);
    }
    public function structure(Request $request)
    {
        if ($request->property_id){
            $data['property'] = Property::where('id', $request->property_id)->where('host_id', Auth::id())->firstOr(function () {
                throw new \Exception('Property not found.');
            });
        }
        $data['currentStep'] = 1;
        $data['totalSteps'] = 8;
        $data['phase'] = 'phase 1';
        $data['categories'] = PropertyCategory::where('status', 1)->get();
        return view(template().'vendor.listing.structure', $data);
    }

    public function structureSave(Request $request)
    {
        try {
            $request->validate([
                'category_id' => 'required|exists:property_categories,id',
                'property_id' => 'nullable|exists:properties,id',
            ]);

            $property = null;

            if ($request->filled('property_id')) {
                $property = Property::where('id', $request->property_id)
                    ->where('host_id', Auth::id())
                    ->first();

                if (!$property) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Property not found.',
                    ], 404);
                }

                if ($property->category_id != $request->category_id) {
                    $property->category_id = $request->category_id;
                    $property->status = in_array($property->status, [1, 2]) ? 2 : 6;
                    $property->save();
                }

            } else {
                $property = new Property();
                $property->host_id = Auth::id();
                $property->category_id = $request->category_id;
                $property->save();
            }

            return response()->json([
                'success' => true,
                'message' => 'Property structure saved successfully.',
                'property' => $property,
                'redirect_url' => route('user.listing.types', ['property_id' => $property->id]),
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->validator->errors()->first(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred.',
            ], 500);
        }
    }
    public function types(Request $request)
    {
        try {
            $property = Property::where('id', $request->property_id)->where('host_id', Auth::id())->firstOr(function () {
                throw new \Exception('Property not found.');
            });

            $data['currentStep'] = 2;
            $data['totalSteps'] = 8;
            $data['phase'] = 'phase 1';
            $data['types'] = PropertyType::where('status', 1)->get();
            return view(template().'vendor.listing.types', compact('property'), $data);
        }catch (\Exception $e){
            return back()->with('error', $e->getMessage());
        }
    }
    public function typeSave(Request $request)
    {
        try {
            $request->validate([
                'property_id' => 'required|exists:properties,id',
                'type_id' => 'required|exists:property_types,id',
            ]);

            $property = Property::where('id', $request->property_id)
                ->where('host_id', Auth::id())
                ->first();

            if (!$property) {
                return response()->json([
                    'success' => false,
                    'message' => 'Property not found.',
                ], 404);
            }

            if (!$property->type_id || $property->type_id != $request->type_id) {
                $property->type_id = $request->type_id;
                $property->status = in_array($property->status, [1, 2]) ? 2 : 6;
                $property->save();
            }

            return response()->json([
                'success' => true,
                'message' => 'Property type saved successfully.',
                'property' => $property,
                'redirect_url' => route('user.listing.styles', ['property_id' => $property->id]),
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->validator->errors()->first(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred.',
            ], 500);
        }
    }
    public function styles(Request $request)
    {
        try {
            $property = Property::where('id', $request->property_id)->where('host_id', Auth::id())->firstOr(function () {
                throw new \Exception('Property not found.');
            });

            $data['currentStep'] = 3;
            $data['totalSteps'] = 8;
            $data['phase'] = 'phase 1';
            $data['styles'] = PropertyStyle::where('status', 1)->get();
            return view(template().'vendor.listing.styles', compact('property'), $data);;
        }catch (\Exception $e){
            return back()->with('error', $e->getMessage());
        }
    }
    public function styleSave(Request $request)
    {
        try {
            $validated = $request->validate([
                'property_id' => 'required|exists:properties,id',
                'style_id' => 'required|integer',
            ]);

            $property = Property::where('id', $validated['property_id'])
                ->where('host_id', Auth::id())
                ->first();

            if (!$property) {
                return response()->json([
                    'success' => false,
                    'message' => 'Property not found.',
                ], 404);
            }

            $property->style_id = $validated['style_id'];
            $property->status = in_array($property->status, [1, 2]) ? 2 : 6;
            $property->save();

            return response()->json([
                'success' => true,
                'message' => 'Property style saved successfully.',
                'property' => $property,
                'redirect_url' => route('user.listing.maps', ['property_id' => $property->id]),
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->validator->errors()->first(),
            ], 422);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again.',
            ], 500);
        }
    }
    public function maps(Request $request)
    {
        try {
            $property = Property::where('id', $request->property_id)->where('host_id', Auth::id())->firstOr(function () {
                throw new \Exception('Property not found.');
            });

            $data['googleMapApiKey'] = basicControl()->google_map_app_key ?? null;
            $data['googleMapId'] = basicControl()->google_map_id ?? null;


            $data['currentStep'] = 4;
            $data['totalSteps'] = 8;
            $data['phase'] = 'phase 1';
            $data['destinations'] = Destination::where('status', 1)->get();
            return view(template().'vendor.listing.maps', compact('property'), $data);;
        }catch (\Exception $e){
            return back()->with('error', $e->getMessage());
        }

    }
    public function mapSave(Request $request)
    {
        try {
            $validated = $request->validate([
                'property_id'   => 'required|exists:properties,id',
                'destination'   => 'required|integer|exists:destinations,id',
                'full_address'  => 'required|string|max:255',
                'lat'           => 'nullable|numeric|between:-90,90',
                'lng'           => 'nullable|numeric|between:-180,180',
                'country'       => 'nullable|string|max:100',
                'state'         => 'nullable|string|max:100',
                'city'          => 'nullable|string|max:100',
                'zip_code'      => 'nullable|string|max:20',
            ]);

            $property = Property::where('id', $validated['property_id'])
                ->where('host_id', Auth::id())
                ->first();

            if (!$property) {
                return response()->json([
                    'success' => false,
                    'message' => 'Property not found.',
                ], 404);
            }

            $property->destination_id = $validated['destination'];
            $property->address        = $validated['full_address'];
            $property->latitude       = $validated['lat'];
            $property->longitude      = $validated['lng'];
            $property->country        = $validated['country'];
            $property->state          = $validated['state'];
            $property->city           = $validated['city'];
            $property->zip_code       = $validated['zip_code'];
            $property->status         = in_array($property->status, [1, 2]) ? 2 : 6;
            $property->save();

            return response()->json([
                'success' => true,
                'message' => 'Property location saved successfully.',
                'property' => $property,
                'redirect_url' => route('user.listing.location', ['property_id' => $property->id]),
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->validator->errors()->first(),
            ], 422);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again.',
            ], 500);
        }
    }
    public function location(Request $request)
    {
        try {
            $property = Property::where('id', $request->property_id)->where('host_id', Auth::id())->firstOr(function () {
                throw new \Exception('Property not found.');
            });

            $data['currentStep'] = 5;
            $data['totalSteps'] = 8;
            $data['phase'] = 'phase 1';
            return view(template().'vendor.listing.location', compact('property'), $data);
        }catch (\Exception $e){
            return back()->with('error', $e->getMessage());
        }
    }
    public function locationSave(Request $request)
    {
        try {
            $validated = $request->validate([
                'property_id'   => 'required|integer|exists:properties,id',
                'address'       => 'required|string|max:255',
                'country'       => 'required|string|max:100',
                'state'         => 'required|string|max:100',
                'city'          => 'required|string|max:100',
                'zip'           => 'required|string|max:20',
            ]);

            $property = Property::where('id', $validated['property_id'])
                ->where('host_id', Auth::id())
                ->firstOr(function () {
                    throw new \Exception('Property not found.');
                });

            $property->address = $validated['address'];
            $property->country = $validated['country'];
            $property->state = $validated['state'];
            $property->city = $validated['city'];
            $property->zip_code = $validated['zip'];
            $property->status = in_array($property->status, [1, 2]) ? 2 : 6;
            $property->save();

            return response()->json([
                'success' => true,
                'message' => 'Location saved successfully.',
                'redirect_url' => route('user.listing.nearby', ['property_id' => $property->id]),
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->validator->errors()->first(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage() ?: 'Something went wrong.',
            ], 500);
        }
    }

    public function nearby(Request $request)
    {
        try {
            $property = Property::where('id', $request->property_id)->where('host_id', Auth::id())->firstOr(function () {
                throw new \Exception('Property not found.');
            });

            $data['googleMapApiKey'] = basicControl()->google_map_app_key ?? null;
            $data['googleMapId'] = basicControl()->google_map_id ?? null;

            $data['currentStep'] = 6;
            $data['totalSteps'] = 8;
            $data['phase'] = 'phase 1';
            return view(template().'vendor.listing.nearby', compact('property'), $data);
        }catch (\Exception $e){
            return back()->with('error', $e->getMessage());
        }
    }

    public function nearbySave(Request $request)
    {
        try {
            $validated = $request->validate([
                'property_id'     => 'required|integer|exists:properties,id',
                'nearby_places'   => 'required|array|min:1',
                'nearby_places.*.title'    => 'required|string|max:255',
                'nearby_places.*.distance' => 'required|numeric|min:0',
                'nearby_places.*.lat' => 'required',
                'nearby_places.*.lng' => 'required',
            ]);

            $property = Property::where('id', $validated['property_id'])
                ->where('host_id', Auth::id())
                ->firstOrFail();

            $property->nearest_places = json_encode($validated['nearby_places']);
            $property->status = in_array($property->status, [1, 2]) ? 2 : 6;
            $property->save();

            return response()->json([
                'success' => true,
                'message' => 'Nearby places saved successfully.',
                'redirect_url' => route('user.listing.informations', ['property_id' => $property->id]),
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->validator->errors()->first(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage() ?: 'Something went wrong.',
            ], 500);
        }
    }
    public function informations(Request $request)
    {
        $property = Property::with('features')
            ->where('id', $request->property_id)
            ->where('host_id', Auth::id())
            ->first();

        if (!$property) {
            return back()->with('error', 'Property not found.');
        }

        $data['currentStep'] = 7;
        $data['totalSteps'] = 8;
        $data['phase'] = 'phase 1';
        return view(template().'vendor.listing.informations', compact('property'), $data);
    }

    public function informationSave(Request $request)
    {
        try {
            $validated = $request->validate([
                'property_id' => 'required|integer|exists:properties,id',
                'guests'      => 'required|integer|min:1',
                'bathrooms'   => 'required|integer|min:0',
                'bedrooms'    => 'required|integer|min:0',
                'beds'        => 'required|integer|min:0',
            ]);

            $property = Property::where('id', $validated['property_id'])
                ->where('host_id', Auth::id())
                ->firstOrFail();

            PropertyFeature::updateOrCreate(
                ['property_id' => $property->id],
                [
                    'max_guests' => $validated['guests'],
                    'bathrooms'  => $validated['bathrooms'],
                    'bedrooms'   => $validated['bedrooms'],
                    'beds'       => $validated['beds'],
                ]
            );

            $property->status = in_array($property->status, [1, 2]) ? 2 : 6;
            $property->save();

            return response()->json([
                'success' => true,
                'message' => 'Property information saved successfully.',
                'redirect_url' => route('user.listing.availablityAndFeatures', ['property_id' => $property->id]),
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->validator->errors()->first(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage() ?: 'Something went wrong.',
            ], 500);
        }
    }

    public function availablityAndFeatures(Request $request)
    {
        $property = Property::with('features')
            ->where('id', $request->property_id)
            ->where('host_id', Auth::id())
            ->first();

        if (!$property) {
            return back()->with('error', 'Property not found.');
        }

        $data['currentStep'] = 8;
        $data['totalSteps'] = 8;
        $data['phase'] = 'phase 1';

        return view(template().'vendor.listing.availablity_and_features', compact('property'), $data);
    }
    public function availablityAndFeatureSave(Request $request)
    {
        try {
            $validated = $request->validate([
                'property_id' => 'required|exists:properties,id',
                'available_from' => 'required|date',
                'available_to'   => 'nullable|date|after_or_equal:available_from',
                'custom_features' => 'nullable|array',
                'custom_features.*.name' => 'nullable|string|max:255',
                'custom_features.*.enabled' => 'nullable|boolean',
            ]);

            $property = Property::where('id', $validated['property_id'])
                ->where('host_id', Auth::id())
                ->firstOrFail();

            $flattenedFeatures = [];
            foreach ($validated['custom_features'] ?? [] as $feature) {
                if (!empty($feature['name']) && array_key_exists('enabled', $feature)) {
                    $flattenedFeatures[$feature['name']] = (string) $feature['enabled'];
                }
            }

            PropertyFeature::updateOrCreate(
                ['property_id' => $property->id],
                ['others' => $flattenedFeatures]
            );

            if (!empty($validated['available_from']) || !empty($validated['available_to'])) {
                Availability::updateOrCreate(
                    ['property_id' => $property->id],
                    [
                        'available_from' => $validated['available_from'],
                        'available_to' => $validated['available_to'],
                    ]
                );
            } else {
                Availability::where('property_id', $property->id)->delete();
            }

            $property->status = in_array($property->status, [1, 2]) ? 2 : 6;
            $property->save();

            return response()->json([
                'success' => true,
                'message' => 'Availability and features saved successfully.',
                'redirect_url' => route('user.listing.stand.out', ['property_id' => $property->id]),
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->validator->errors()->first(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage() ?: 'Something went wrong.',
            ], 500);
        }
    }
    public function standOut(Request $request)
    {
        $property = Property::where('id', $request->property_id)
            ->where('host_id', Auth::id())
            ->first();

        if (!$property) {
            return back()->with('error', 'Property not found.');
        }

        $data['currentStep'] = 0;
        $data['totalSteps'] = 4;
        $data['phase'] = 'phase 2';

        return view(template().'vendor.listing.stand_out', compact('property'), $data);
    }
    public function amenities(Request $request)
    {
        $property = Property::with('features')
            ->where('id', $request->property_id)
            ->where('host_id', Auth::id())
            ->first();

        if (!$property) {
            return back()->with('error', 'Property not found.');
        }

        $data['amenities'] = Amenity::where('status', 1)->get();

        $data['currentStep'] = 1;
        $data['totalSteps'] = 4;
        $data['phase'] = 'phase 2';
        return view(template().'vendor.listing.amenities',compact('property'), $data);
    }

    public function amenitiesSave(Request $request)
    {
        try {
            $validated = $request->validate([
                'property_id' => 'required|exists:properties,id',
                'amenities' => ['required', 'regex:/^(\d+,)*\d+$/'],
            ]);

            $property = Property::where('id', $validated['property_id'])
                ->where('host_id', Auth::id())
                ->firstOrFail();

            $amenityIds = explode(',', $validated['amenities']);

            $amenities = Amenity::whereIn('id', $amenityIds)
                ->where('status', 1)
                ->get();

            $formattedAmenities = [
                'amenity' => [],
                'favourites' => [],
                'safety_item' => [],
            ];

            $allowedTypes = array_keys($formattedAmenities);

            foreach ($amenities as $amenity) {
                if (in_array($amenity->type, $allowedTypes)) {
                    $formattedAmenities[$amenity->type][] = (string) $amenity->id;
                }
            }

            PropertyAmenity::updateOrCreate(
                ['property_id' => $property->id],
                ['amenities' => $formattedAmenities]
            );

            $property->status = in_array($property->status, [1, 2]) ? 2 : 6;
            $property->save();

            return response()->json([
                'success' => true,
                'message' => 'Amenities saved successfully.',
                'redirect_url' => route('user.listing.photos', ['property_id' => $property->id]),
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->validator->errors()->first(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage() ?: 'Something went wrong.',
            ], 500);
        }
    }
    public function photos(Request $request)
    {
        $property = Property::where('id', $request->property_id)
            ->where('host_id', Auth::id())
            ->first();

        if (!$property) {
            return back()->with('error', 'Property not found.');
        }

        $data['currentStep'] = 2;
        $data['totalSteps'] = 4;
        $data['phase'] = 'phase 2';
        return view(template().'vendor.listing.photos', compact('property'), $data);
    }

    public function photosSave(Request $request)
    {
        try {
            $request->validate([
                'property_id' => 'required|exists:properties,id',
                'thumb' => 'nullable|image|mimes:jpeg,png,webp,jpg',
                'images.*' => 'nullable|image|mimes:jpeg,png,webp,jpg',
                'newTitles' => 'nullable|array',
                'existingIndexes' => 'nullable|array',
                'existingTitles' => 'nullable|array',
            ]);

            $property = Property::where('id', $request->property_id)
                ->where('host_id', Auth::id())
                ->firstOrFail();

            $thumb = null;

            if ($request->hasFile('thumb')) {
                $image = $this->fileUpload($request->thumb, config('filelocation.propertyThumb.path'), null, config('filelocation.propertyThumb.size'), 'webp', 80);
                $thumb = [
                    'path' => $image['path'],
                    'driver' => $image['driver']
                ];
            }

            $formattedImagePaths = [];

            if ($request->hasFile('images')) {
                foreach ($request->images as $index => $img) {
                    $image = $this->fileUpload($img, config('filelocation.property.path'), null, config('filelocation.property.size'), 'webp', 80);
                    $formattedImagePaths[$index] = [
                        'path' => $image['path'],
                        'driver' => $image['driver'],
                        'title' => $request->newTitles[$index] ?? null
                    ];
                }
            }

            if (!$thumb && $property->photos && isset($property->photos->images['thumb'])) {
                $thumb = $property->photos->images['thumb'];
            }

            $images = [];

            if ($request->filled('existingIndexes')) {
                $images = $property->photos->images['images'] ?? [];
                $originalIndexes = array_keys($images);
                $existingIndexes = array_map('intval', $request->existingIndexes);
                $deletedIndexes = array_diff($originalIndexes, $existingIndexes);

                foreach ($deletedIndexes as $index) {
                    $image = $images[$index];
                    $this->fileDelete($image['driver'], $image['path']);
                    unset($images[$index]);
                }

                foreach ($images as $index => $imageItem) {
                    if (isset($request->existingTitles[$index])) {
                        $images[$index]['title'] = $request->existingTitles[$index];
                    }
                }

                $images = array_values($images);
            }

            if (!$thumb && count($formattedImagePaths) === 0 && count($images) === 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'You must provide at least a thumbnail or one property image.'
                ], 422);
            }

            $finalImages = array_merge($images, $formattedImagePaths);

            PropertyPhotos::updateOrCreate(
                ['property_id' => $property->id],
                [
                    'images' => [
                        'thumb' => $thumb,
                        'images' => $finalImages
                    ]
                ]
            );

            $property->status = in_array($property->status, [1, 2]) ? 2 : 6;
            $property->save();

            return response()->json([
                'success' => true,
                'message' => 'Photos saved successfully.',
                'redirect_url' => route('user.listing.title', ['property_id' => $property->id]),
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->validator->errors()->first(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
    public function title(Request $request)
    {
        $property = Property::where('id', $request->property_id)
            ->where('host_id', Auth::id())
            ->first();

        if (!$property) {
            return back()->with('error', 'Property not found.');
        }

        $data['currentStep'] = 3;
        $data['totalSteps'] = 4;
        $data['phase'] = 'phase 2';
        return view(template().'vendor.listing.title', compact('property'), $data);
    }
    public function titleSave(Request $request)
    {
        try {
            $request->validate([
                'property_id' => 'required|exists:properties,id',
                'title' => 'required|string|max:100',
            ]);

            $property = Property::where('id', $request->property_id)
                ->where('host_id', Auth::id())
                ->firstOrFail();

            $decodedTitle = html_entity_decode($request->title);

            $property->title = $decodedTitle;
            $property->slug = slug($decodedTitle);
            $property->status = in_array($property->status, [1, 2]) ? 2 : 6;
            $property->save();

            return response()->json([
                'success' => true,
                'message' => 'Title saved successfully.',
                'redirect_url' => route('user.listing.description', ['property_id' => $property->id]),
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->validator->errors()->first(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred.',
            ], 500);
        }
    }

    public function description(Request $request)
    {
        $property = Property::with('features')
            ->where('id', $request->property_id)
            ->where('host_id', Auth::id())
            ->first();

        if (!$property) {
            return back()->with('error', 'Property not found.');
        }

        $data['currentStep'] = 4;
        $data['totalSteps'] = 4;
        $data['phase'] = 'phase 2';
        return view(template().'vendor.listing.description', compact('property'), $data);
    }

    public function descriptionSave(Request $request)
    {
        try {
            $request->validate([
                'property_id' => 'required|exists:properties,id',
                'description' => 'required|string|max:10000',
            ]);

            $property = Property::where('id', $request->property_id)
                ->where('host_id', Auth::id())
                ->firstOrFail();

            $property->description = $request->description;
            $property->status = in_array($property->status, [1, 2]) ? 2 : 6;
            $property->save();

            return response()->json([
                'success' => true,
                'message' => 'Description saved successfully.',
                'redirect_url' => route('user.listing.finishing.setup', ['property_id' => $property->id]),
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->validator->errors()->first(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred.',
            ], 500);
        }
    }
    public function finishingSetup(Request $request)
    {
        $property = Property::where('id', $request->property_id)
            ->where('host_id', Auth::id())
            ->first();

        if (!$property) {
            return back()->with('error', 'Property not found.');
        }

        $data['currentStep'] = 0;
        $data['totalSteps'] = 3;
        $data['phase'] = 'phase 3';
        return view(template().'vendor.listing.finishing_setup', compact('property'), $data);
    }
    public function pricing(Request $request)
    {
        $property = Property::where('id', $request->property_id)
            ->where('host_id', Auth::id())
            ->first();

        if (!$property) {
            return back()->with('error', 'Property not found.');
        }

        $data['currentStep'] = 1;
        $data['totalSteps'] = 4;
        $data['phase'] = 'phase 3';
        return view(template().'vendor.listing.pricing', compact('property'), $data);
    }
    public function pricingSave(Request $request)
    {
        try {
            $request->validate([
                'property_id' => 'required|exists:properties,id',
                'nightly_price' => 'required|numeric|min:0',
                'weekly_price' => 'nullable|numeric|min:0',
                'monthly_price' => 'nullable|numeric|min:0',
                'cleaning_fee' => 'nullable|numeric|min:0',
                'service_fee' => 'nullable|numeric|min:0',
                'is_refundable'        => 'required|in:0,1',
                'refund_policy'        => 'nullable',
            ]);

            $property = Property::with('features')
                ->where('id', $request->property_id)
                ->where('host_id', Auth::id())
                ->firstOrFail();

            $refundInfos = [];

            if ($request->has('refund_rules')) {
                $percentages = $request->input('refund_rules.percentage', []);
                $days = $request->input('refund_rules.days', []);
                $messages = $request->input('refund_rules.message', []);

                $count = max(count($percentages), count($days), count($messages));

                for ($i = 0; $i < $count; $i++) {
                    $refundInfos[] = [
                        'percentage' => $percentages[$i] ?? null,
                        'days'       => $days[$i] ?? null,
                        'message'    => $messages[$i] ?? '',
                    ];
                }
            }

            Pricing::updateOrCreate(
                ['property_id' => $property->id],
                [
                    'nightly_rate' => $request->nightly_price,
                    'weekly_rate' => $request->weekly_price,
                    'monthly_rate' => $request->monthly_price,
                    'cleaning_fee' => $request->cleaning_fee,
                    'service_fee' => $request->service_fee,
                    'refundable' => $request->is_refundable,
                    'refund_infos' => $refundInfos,
                ]
            );

            $property->status = in_array($property->status, [1, 2]) ? 2 : 6;
            $property->save();

            return response()->json([
                'success' => true,
                'message' => 'Pricing details saved successfully.',
                'redirect_url' => route('user.listing.discounts', ['property_id' => $property->id]),
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->validator->errors()->first(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred.',
            ], 500);
        }
    }

    public function discounts(Request $request)
    {
        $property = Property::where('id', $request->property_id)
            ->where('host_id', Auth::id())
            ->first();

        if (!$property) {
            return back()->with('error', 'Property not found.');
        }

        $data['currentStep'] = 2;
        $data['totalSteps'] = 4;
        $data['phase'] = 'phase 3';
        return view(template().'vendor.listing.discounts', compact('property'), $data);
    }

    public function discountsSave(Request $request)
    {

        try {
            $request->validate([
                'property_id' => 'required|exists:properties,id',
                'discounts' => 'required|array',
                'discounts.new_listing.percent' => 'required_if:discounts.new_listing.enabled,on|numeric|min:0|max:100',
                'discounts.weekly.percent' => 'required_if:discounts.weekly.enabled,on|numeric|min:0|max:100',
                'discounts.monthly.percent' => 'required_if:discounts.monthly.enabled,on|numeric|min:0|max:100',
                'custom_discounts' => 'nullable|array',
                'custom_discounts.*.enabled' => 'nullable|in:on',
                'custom_discounts.*.percent' => 'nullable|numeric|min:0|max:100',
                'custom_discounts.*.title' => 'nullable|string|max:255',
                'custom_discounts.*.description' => 'nullable|string|max:1000',
            ]);

            $property = Property::where('id', $request->property_id)
                ->where('host_id', Auth::id())
                ->first();

            if (!$property) {
                return response()->json([
                    'success' => false,
                    'message' => 'Property not found.'
                ], 404);
            }

            $discounts = $request->discounts ?? [];
            $customDiscounts = $request->custom_discounts ?? [];

            if (!empty($customDiscounts)) {
                $others = [];
                $i = 1;
                foreach ($customDiscounts as $custom) {
                    if (isset($custom['enabled']) && $custom['enabled'] === 'on') {
                        $others[$i++] = $custom;
                    }
                }
                if (!empty($others)) {
                    $discounts['others'] = $others;
                }
            }

            $hasEnabledDiscount = collect($discounts)->contains(function ($item) {
                return isset($item['enabled']) && $item['enabled'] === 'on';
            });

            $property->discount = $hasEnabledDiscount ? 1 : 0;
            $property->discount_info = $discounts;
            $property->status = in_array($property->status, [1, 2]) ? 2 : 6;
            $property->save();

            return response()->json([
                'success' => true,
                'message' => 'Discounts saved successfully.',
                'redirect_url' => route('user.listing.safety', ['property_id' => $property->id]),
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->validator->errors()->first(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred.',
            ], 500);
        }
    }

    public function safety(Request $request)
    {
        $property = Property::where('id', $request->property_id)
            ->where('host_id', Auth::id())
            ->first();

        if (!$property) {
            return back()->with('error', 'Property not found.');
        }

        $data['currentStep'] = 3;
        $data['totalSteps'] = 4;
        $data['phase'] = 'phase 3';
        return view(template().'vendor.listing.safety', compact('property'), $data);
    }

    public function safetySave(Request $request)
    {
        try {
            $request->validate([
                'property_id' => 'required|exists:properties,id',
                'safety' => 'nullable|array',
                'safety.*' => 'string|max:255',
                'safety_custom' => 'nullable|array',
                'safety_custom.*.label' => 'nullable|string|max:255',
            ]);

            $property = Property::where('id', $request->property_id)
                ->where('host_id', Auth::id())
                ->first();

            if (!$property) {
                return response()->json([
                    'success' => false,
                    'message' => 'Property not found.'
                ], 404);
            }

            $coreItems = $request->input('safety', []);

            $customItems = collect($request->input('safety_custom', []))
                ->pluck('label')
                ->filter()
                ->values()
                ->toArray();

            $property->safety_items = [
                'core' => $coreItems,
                'others' => $customItems,
            ];

            $property->status = in_array($property->status, [1, 2]) ? 2 : 6;
            $property->save();

            return response()->json([
                'success' => true,
                'message' => 'Safety information saved successfully.',
                'redirect_url' => route('user.listing.rules', ['property_id' => $property->id])
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->validator->errors()->first(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred.',
            ], 500);
        }
    }
    public function rules(Request $request)
    {
        $property = Property::where('id', $request->property_id)
            ->where('host_id', Auth::id())
            ->first();

        if (!$property) {
            return back()->with('error', 'Property not found.');
        }

        $data['currentStep'] = 4;
        $data['totalSteps'] = 4;
        $data['phase'] = 'phase 3';
        return view(template().'vendor.listing.rules', compact('property'), $data);
    }

    public function rulesSave(Request $request)
    {
        try {
            $request->validate([
                'property_id' => 'required|exists:properties,id',
                'house_rules' => 'nullable|array',
                'house_rules.*' => 'string|max:255',
            ]);

            $property = Property::where('id', $request->property_id)
                ->where('host_id', Auth::id())
                ->first();

            if (!$property) {
                return response()->json([
                    'success' => false,
                    'message' => 'Property not found.'
                ], 404);
            }



            $property->rules = $request->input('house_rules', []);

            $property->status = in_array($property->status, [1, 2]) ? 2 : 6;
            $property->save();

            return response()->json([
                'success' => true,
                'message' => 'Rules information saved successfully.',
                'redirect_url' => route('user.listing.finish', ['property_id' => $property->id])
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->validator->errors()->first(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred.',
            ], 500);
        }
    }
    public function finish(Request $request)
    {
        $request->validate([
            'property_id' => 'required|exists:properties,id',
        ]);

        $property = Property::where('id', $request->property_id)
            ->where('host_id', Auth::id())
            ->first();

        $params = [
            'username' => optional($property->host)->username,
            'title'    => $property->title,
            'status'   => match ($property->status) {
                0 => 'In Progress',
                6 => 'Pending',
                2 => 'Re Submission',
                default => 'Approved',
            },
        ];

        $actionAdmin = [
            "name" => optional($property->host)->firstname . ' ' . optional($property->host)->lastname,
            "image" => getFile(optional($property->host)->image_driver, optional($property->host)->image),
            "link" => route('admin.all.property'),
            "icon" => "fas fa-ticket-alt text-white"
        ];

        $this->adminMail('PROPERTY_CREATED', $params, $actionAdmin);
        $this->adminPushNotification('PROPERTY_CREATED', $params, $actionAdmin);
        $this->adminFirebasePushNotification('PROPERTY_CREATED', $params);

        return redirect(route('user.property.list'))->with('success', '🎉 Great job! Your property listing is now pending and awaiting admin review.');

    }
    public function generate(Request $request)
    {
        if (isAiAccess()){
            $basicControl = basicControl();
            if ($basicControl->open_ai_status) {
                $openAiService = new OpenAiService();
                $promt = getPromt($request);
                $res = $openAiService->generateRes($promt);
            } elseif ($basicControl->gemini_status) {
                $geminiService = new GeminiService();
                $promt = getPromt($request);
                $res = $geminiService->generateRes($promt);
            }
        }else {
            $res = [
                'success' => false,
                'message' => 'Access to AI services is denied.',
            ];
        }

        return response()->json([
            'type' => $request->type,
            'res' => $res
        ]);
    }
    public function generateImage(Request $request)
    {
        if (isAiAccess()) {
            $basicControl = basicControl();

            if (isset($request->image_count) && $request->image_count > 3) {
                $res = [
                    'success' => false,
                    'message' => 'Maximum 3 images allowed.',
                ];
                return response()->json($res);
            }

            if ($basicControl->open_ai_status) {
                $openAiService = new OpenAiService();
                $res = $openAiService->generateImage($request);
            } elseif ($basicControl->gemini_status) {
                $geminiService = new GeminiService();
                $res = $geminiService->generateImage($request);
            }

            $type = $request->image_type ?? null;
            $resData = session()->get('generated_images', []);

            if ($type && isset($res['image_data_uris'])) {
                if (isset($resData[$type]['image_data_uris'])) {
                    $resData[$type]['image_data_uris'] = array_merge(
                        $res['image_data_uris'],
                        $resData[$type]['image_data_uris']
                    );
                    $resData[$type]['message'] = count($resData[$type]['image_data_uris']) . ' image(s) stored in session.';
                    $resData[$type]['status'] = 'success';
                } else {
                    $resData[$type] = $res;
                }

                session()->put('generated_images', $resData);
            }
        } else {
            $res = [
                'success' => false,
                'message' => 'Access to AI services is denied.',
            ];
        }

        $res['type'] = $type;
        $res['session_data'] = $resData;
        return response()->json($res);
    }

    public function icalUpdate(Request $request)
    {
        $request->validate([
            'property_id'    => 'required|integer|exists:properties,id',
            'source_name.*'  => 'required|string|max:255',
            'ical_url.*'     => 'required|url',
        ]);

        $propertyId = $request->property_id;

        $sourcesData = [];
        foreach ($request->source_name as $index => $name) {
            $sourcesData[] = [
                'property_id' => $propertyId,
                'source_name' => $name,
                'ical_url'    => $request->ical_url[$index],
                'created_at'  => now(),
                'updated_at'  => now(),
            ];
        }

        PropertyIcalSource::where('property_id', $propertyId)->delete();
        PropertyIcalSource::insert($sourcesData);

        return response()->json([
            'status'  => true,
            'message' => 'ICS Sources updated successfully!',
        ]);
    }

    public function icalGet($propertyId)
    {
        $sources = PropertyIcalSource::where('property_id', $propertyId)->get();

        return response()->json([
            'sources' => $sources
        ]);
    }
}
