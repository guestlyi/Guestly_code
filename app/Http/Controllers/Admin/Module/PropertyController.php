<?php

namespace App\Http\Controllers\Admin\Module;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Amenity;
use App\Models\City;
use App\Models\Country;
use App\Models\Destination;
use App\Models\Property;
use App\Models\PropertyCategory;
use App\Models\PropertySeo;
use App\Models\PropertyStyle;
use App\Models\PropertyType;
use App\Models\SellPost;
use App\Models\State;
use App\Traits\Notify;
use App\Traits\Upload;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use PhpOffice\PhpSpreadsheet\Calculation\Category;
use Yajra\DataTables\Facades\DataTables;

class PropertyController extends Controller
{
    use Upload, Notify;

    public function list(Request $request)
    {
        $data['category'] = $request->category;
        $data['country'] = $request->country;
        $data['state'] = $request->state;
        $data['city'] = $request->city;
        $data['amenity'] = $request->amenity;
        $data['type'] = $request->type;
        $data['style'] = $request->style;
        $data['destination'] = $request->destination;


        $currentMonth = now()->month;
        $currentYear = now()->year;

        $data['propertyStats'] = Property::selectRaw("
            COUNT(*) as total,
            SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END) as active,
            SUM(CASE WHEN status = 0 THEN 1 ELSE 0 END) as inactive,
            SUM(CASE WHEN MONTH(created_at) = ? AND YEAR(created_at) = ? THEN 1 ELSE 0 END) as this_month,
            SUM(CASE WHEN YEAR(created_at) = ? THEN 1 ELSE 0 END) as this_year
        ", [$currentMonth, $currentYear, $currentYear])->first();

        $total = $data['propertyStats']->total ?: 1;

        $data['percentages'] = [
            'active' => round(($data['propertyStats']->active / $total) * 100, 2),
            'inactive' => round(($data['propertyStats']->inactive / $total) * 100, 2),
            'this_month' => round(($data['propertyStats']->this_month / $total) * 100, 2),
            'this_year' => round(($data['propertyStats']->this_year / $total) * 100, 2),
        ];

        return view('admin.property.list', $data);
    }

    public function listSearch(Request $request)
    {
        $search = $request->search['value'];
        $filterSearch = $request->filterSearch;
        $filterStatus = $request->filterStatus;
        $filterDate = explode('-', $request->filterDate);
        $startDate = $filterDate[0];
        $endDate = isset($filterDate[1]) ? trim($filterDate[1]) : null;
        $category = $request->category;
        $country = $request->country;
        $state = $request->state;
        $city = $request->city;
        $amenity = $request->amenity;
        $type = $request->type;
        $style = $request->style;
        $destination = $request->destination;

        $property = Property::query()
            ->with(['category:id,name', 'photos:id,property_id,images', 'allAmenity:id,property_id,amenities', 'pricing'])
            ->orderBy('id', 'DESC')
            ->when(!empty($search), function ($query) use ($search) {
                return $query->where('title', 'LIKE', "%{$search}%");
            })
            ->when(isset($filterSearch) && !empty($filterSearch), function ($query) use ($filterSearch) {
                return $query->where('title', 'LIKE', "%{$filterSearch}%");
            })
            ->when(isset($filterStatus), function ($query) use ($filterStatus) {
                if ($filterStatus == 'all') {
                    return $query->where('status', '!=', null);
                }
                return $query->where('status', '=', $filterStatus);
            })
            ->when(isset($category), function ($query) use ($category) {
                return $query->where('category_id', $category);
            })
            ->when(isset($destination), function ($query) use ($destination) {
                return $query->where('destination_id', $destination);
            })
            ->when(isset($country), function ($query) use ($country) {
                return $query->where('country', $country);
            })
            ->when(isset($state), function ($query) use ($state) {
                return $query->where('state', $state);
            })
            ->when(isset($city), function ($query) use ($city) {
                return $query->where('city', $city);
            })
            ->when(isset($type), function ($query) use ($type) {
                return $query->where('type_id', $type);
            })
            ->when(isset($style), function ($query) use ($style) {
                return $query->where('style_id', $style);
            })
            ->when(isset($amenity), function ($query) use ($amenity) {
                $query->whereHas('allAmenity', function ($query) use ($amenity) {
                    $query->whereRaw("JSON_SEARCH(amenities, 'one', ? ) IS NOT NULL", [$amenity]);
                });
            })
            ->when(!empty($request->filterDate) && $endDate != null, function ($query) use ($startDate, $endDate) {
                $startDate = Carbon::createFromFormat('d/m/Y', trim($startDate));
                $endDate = Carbon::createFromFormat('d/m/Y', trim($endDate));
                $query->whereBetween('created_at', [$startDate, $endDate]);
            });

        return DataTables::of($property)
            ->addColumn('checkbox', function ($item) {
                return ' <input type="checkbox" id="chk-' . $item->id . '"
                                       class="form-check-input row-tic tic-check" name="check" value="' . $item->id . '"
                                       data-id="' . $item->id . '">';
            })
            ->addColumn('name', function ($item) {
                $image = $item->photos?->images['thumb'];
                $firstLetter = substr($item->title, 0, 1);
                $titleHtml = '<span class="fs-6 text-body ps-3">' . Str::limit($item->title, 30) . '</span>';

                if (!$image) {
                    return '<div class="d-flex align-items-center">
                        <div class="avatar avatar-xl avatar-soft-primary" style="font-size: 22px;">
                            <span class="avatar-initials">' . e($firstLetter) . '</span>
                        </div>
                        ' . $titleHtml . '
                    </div>';
                } else {
                    $url = getFile($image['driver'], $image['path']);
                    return '<div class="d-flex align-items-center">
                        <div class="avatar avatar-xl">
                            <img class="avatar-img" src="' . e($url) . '" alt="Service Image">
                        </div>
                        ' . $titleHtml . '
                    </div>';
                }
            })
            ->addColumn('host', function ($item) {
                $titlePart = $item->host ? ($item->host->firstname. ' ' .$item->host->lastname) : 'Unknown';
                $title = trim($titlePart);
                $shortTitle = strlen($title) > 30 ? substr($title, 0, 30) . '...' : $title;
                $username = '@' . ($item->host->username ?? 'unknown');

                if (empty($item->host->image)) {
                    $firstLetter = strtoupper(substr($title, 0, 1));

                    return '<a class="d-flex align-items-center me-2" href="javascript:void(0)" title="' . e($title) . '">
                        <div class="flex-shrink-0">
                            <div class="avatar avatar-sm avatar-soft-primary avatar-circle">
                                <span class="avatar-initials">' . e($firstLetter) . '</span>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="text-hover-primary mb-0" title="' . e($title) . '">' . e($shortTitle) . '</h5>
                            <p>' . e($username) . '</p>
                        </div>
                    </a>';
                } else {
                    $url = getFile($item->host->image_driver, $item->host->image);

                    return '<a class="d-flex align-items-center me-2" href="javascript:void(0)" title="' . e($title) . '">
                        <div class="flex-shrink-0">
                            <div class="avatar avatar-sm avatar-circle">
                                <img class="avatar-img" src="' . $url . '" alt="Image Description">
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="text-hover-primary mb-0" title="' . e($title) . '">' . e($shortTitle) . '</h5>
                            <p>' . e($username) . '</p>
                        </div>
                    </a>';
                }
            })
            ->addColumn('category', function ($item) {
                return $item->category?->name;
            })
            ->addColumn('amenity', function ($item) {
                $amenitiesData = is_string($item->allAmenity?->amenities) ? json_decode($item->allAmenity?->amenities, true) : $item->allAmenity?->amenities;

                $types = [
                    'Amenity' => $amenitiesData['amenity'] ?? [],
                    'Favourites' => $amenitiesData['favourites'] ?? [],
                    'Safety Item' => $amenitiesData['safety_item'] ?? [],
                ];

                $result = [];
                $total = 0;

                foreach ($types as $type => $ids) {
                    if (!empty($ids)) {
                        $names = Amenity::whereIn('id', $ids)->pluck('title')->toArray();
                        if (!empty($names)) {
                            $result[] = [
                                'type' => $type,
                                'names' => $names
                            ];
                            $total += count($names);
                        }
                    }
                }

                $amenitiesJson = htmlspecialchars(json_encode($result), ENT_QUOTES, 'UTF-8');
                $displayCount = max(0, $total - 1);

                return "<button type='button' class='btn btn-sm show-amenities' data-bs-toggle='tooltip' title='View Amenities' data-amenities='{$amenitiesJson}'>
                {$displayCount}+
            </button>";
            })
            ->addColumn('price', function ($item) {
                return '
                    <span class="badge bg-soft-info text-info me-1">' . trans('Nightly Price') . '</span><span class="badge bg-soft-secondary text-secondary"> ' . currencyPosition($item->pricing->nightly_rate ?? 0) . '</span><br>
                    <span class="badge bg-soft-info text-info">' . trans('Weekly Price') . '</span> <span class="badge bg-soft-secondary text-secondary">' . currencyPosition($item->pricing->weekly_rate ?? 0) . '</span><br>
                    <span class="badge bg-soft-info text-info">' . trans('Monthly Price') . '</span> <span class="badge bg-soft-secondary text-secondary">' . currencyPosition($item->pricing->monthly_rate ?? 0) . '</span>
                ';
            })
            ->addColumn('country', function ($item) {
                return $item->country;
            })
            ->addColumn('status', function ($item) {
                if ($item->status == 1) {
                    return '<span class="badge bg-soft-success text-success">
                                <span class="legend-indicator bg-success"></span>' . trans('Active') . '
                              </span>';
                } elseif ($item->status == 0) {
                    return '<span class="badge bg-soft-warning text-warning">
                                <span class="legend-indicator bg-warning"></span>' . trans('Incomplete') . '
                              </span>';
                } elseif ($item->status == 2) {
                    return '<span class="badge bg-soft-primary text-primary">
                                <span class="legend-indicator bg-primary"></span>' . trans('Re Submission') . '
                              </span>';
                }elseif ($item->status == 3) {
                    return '<span class="badge bg-soft-secondary text-secondary">
                                <span class="legend-indicator bg-secondary"></span>' . trans('Hold') . '
                              </span>';
                }elseif ($item->status == 4) {
                    return '<span class="badge bg-soft-info text-info">
                                <span class="legend-indicator bg-info"></span>' . trans('Soft Rejected') . '
                              </span>';
                }elseif ($item->status == 5) {
                    return '<span class="badge bg-soft-danger text-danger">
                                <span class="legend-indicator bg-danger"></span>' . trans('Hard Rejected') . '
                              </span>';
                }elseif ($item->status == 6) {
                    return '<span class="badge bg-soft-warning text-warning">
                                <span class="legend-indicator bg-warning"></span>' . trans('Pending') . '
                              </span>';
                }else{
                    return '<span class="badge bg-soft-dark text-dark">
                                <span class="legend-indicator bg-dark"></span>' . trans('Unknown') . '
                              </span>';
                }
            })
            ->addColumn('action', function ($item) {
                $editUrl = route('admin.property.edit', $item->id);
                $deleteUrl = route('admin.property.delete', $item->id);
                $seoUrl = route('admin.property.seo', $item->id);
                $bookingUrl = route('admin.all.booking', ['property_id' => $item->id]);
                $reviewUrl = route('admin.review.list', ['property_id' => $item->id]);
                return '<div class="btn-group" role="group">
                      <a href="' . $editUrl . '" class="btn btn-white btn-sm edit_user_btn">
                        <i class="bi-pencil-square me-1"></i> ' . trans("Edit") . '
                      </a>
                    <div class="btn-group">
                      <button type="button" class="btn btn-white btn-icon btn-sm dropdown-toggle dropdown-toggle-empty" id="userEditDropdown" data-bs-toggle="dropdown" aria-expanded="false"></button>
                      <div class="dropdown-menu dropdown-menu-end mt-1" aria-labelledby="userEditDropdown">
                        <a class="dropdown-item" href="'. $seoUrl .'">
                            <i class="bi bi-graph-up dropdown-item-icon"></i>
                            '. trans("SEO") .'
                        </a>
                        <a class="dropdown-item" href="'. $bookingUrl .'">
                            <i class="bi-calendar dropdown-item-icon"></i>
                            '. trans("Bookings") .'
                        </a>
                        <a class="dropdown-item" href="'. $reviewUrl .'">
                            <i class="bi bi-star-fill dropdown-item-icon"></i>
                            '.trans("Reviews").'
                        </a>
                        <a class="dropdown-item deleteSingleBtn text-danger" href="javascript:void(0)"
                           data-route="' . $deleteUrl . '"
                           data-bs-toggle="modal" data-bs-target="#deleteModal">
                            <i class="bi bi-trash dropdown-item-icon text-danger"></i>
                            ' . trans("Delete") . '
                        </a>
                      </div>
                    </div>
                  </div>';
            })->rawColumns(['action', 'checkbox', 'name', 'category', 'status', 'price', 'amenity', 'country','host'])
            ->make(true);
    }

    public function edit($id)
    {
        try {
            $data['property'] = Property::with(['photos:id,property_id,images', 'allAmenity:id,property_id,amenities', 'pricing'])->where('id', $id)->firstOr(function () {
                throw new \Exception('This Property is not available now');
            });
            $data['categories'] = PropertyCategory::where('status', 1)->get();
            $data['destinations'] = Destination::where('status', 1)->get();
            $data['types'] = PropertyType::where('status', 1)->get();
            $data['styles'] = PropertyStyle::where('status', 1)->get();
            $data['amenities'] = Amenity::where('status', 1)->get();
            $data['images'] = collect($data['property']->photos->images['images'] ?? [])
                ->map(fn($item) => getFile($item['driver'], $item['path']))
                ->toArray();
            $data['oldimg'] = array_keys($data['property']->photos?->images['images'] ?? []);
            $data['location'] = Country::where('status', 1)->orderBy('name', 'asc')->get();
            $data['activity'] = ActivityLog::where('property_id', $id)->with('activityable:id,username,image,image_driver')->orderBy('id', 'desc')->get();

            return view('admin.property.edit', $data);
        } catch (\Exception $exception) {
            return back()->with("error", $exception->getMessage());
        }
    }

    public function update(Request $request)
    {
        DB::beginTransaction();
        $request->validate([
            'property_id' => 'required|integer|exists:properties,id',
            'category_id' => 'required|integer|exists:property_categories,id',
            'destination_id' => 'required|integer|exists:destinations,id',
            'type_id' => 'required|integer|exists:property_types,id',
            'style_id' => 'required|integer|exists:property_styles,id',
            'title' => 'required|string|max:255',
            'slug' => [
                'required',
                'max:255',
                Rule::unique('properties', 'slug')->ignore($request->property_id, 'id')
            ],
            'description' => 'required|string',
            'amenities_id' => 'required|array',
            'amenities_id.*' => 'integer|exists:amenities,id',
            'city' => 'required',
            'state' => 'required',
            'country' => 'required',
            'nightly_rate' => 'required|numeric|min:0',
            'weekly_rate' => 'required|numeric|min:0',
            'monthly_rate' => 'required|numeric|min:0',
            'cleaning_fee' => 'required|numeric|min:0',
            'service_fee' => 'required|numeric|min:0',
            'max_guests' => 'required|numeric|min:0',
            'bedrooms' => 'required|numeric|min:0',
            'bathrooms' => 'required|numeric|min:0',
            'refund_infos' => 'nullable|array',
            'refundable' => 'required|in:0,1',
            'others' => 'nullable|array',
            'images' => 'nullable|array',
            'images.*' => 'file|mimes:jpeg,png,jpg,gif,svg|max:10240',
        ]);
        try {

            $property = Property::where('id', $request->property_id)->firstOr(function () {
                throw new \Exception('This Property is not available now');
            });

            $amenities = Amenity::whereIn('id', $request->amenities_id)
                ->where('status', 1)
                ->get();

            $formattedAmenities = [
                'amenity' => [],
                'favourites' => [],
                'safety_item' => []
            ];

            foreach ($amenities as $amenity) {
                if ($amenity->type == 'amenity') {
                    $formattedAmenities['amenity'][] = (string)$amenity->id;
                } elseif ($amenity->type == 'favourites') {
                    $formattedAmenities['favourites'][] = (string)$amenity->id;
                } elseif ($amenity->type == 'safety_item') {
                    $formattedAmenities['safety_item'][] = (string)$amenity->id;
                }
            }

            if ($request->hasFile('thumb')) {
                $image = $this->fileUpload($request->thumb, config('filelocation.propertyThumb.path'), null, config('filelocation.propertyThumb.size'), 'webp', 80, $property->photos->images['thumb']['path'], $property->photos->images['thumb']['driver']);
                $thumb = [
                    'path' => $image['path'],
                    'driver' => $image['driver']
                ];
            }

            $images = $property->photos->images['images'] ?? [];
            $oldimgKey = $request->preloaded ?? [];
            $oldimgKey = array_map('intval', $oldimgKey);
            $remainingImages = array_intersect_key($images, array_flip($oldimgKey));
            $removedImages = array_diff_key($images, array_flip($oldimgKey));

            foreach ($removedImages as $image) {
                $this->fileDelete($image['driver'], $image['path']);
            }


            $formattedPaths = [];
            if ($request->hasFile('images')) {
                foreach ($request->images as $index => $img) {
                    $image = $this->fileUpload($img, config('filelocation.property.path'), null, config('filelocation.property.size'), 'webp', 80);

                    $formattedPaths[] = [
                        'path' => $image['path'],
                        'driver' => $image['driver']
                    ];
                }
            }
            $finalImages = array_merge($remainingImages, $formattedPaths);
            $formattedData = [
                'thumb' => $thumb ?? $property->photos?->images['thumb'],
                'images' => $finalImages
            ];

            if ($property->country != $request->country || $property->state != $request->state || $property->city != $request->city || $property->address != $request->address){
                $result = getMap($request->country, $request->state, $request->city, $request->address);
                $lat = $result[0];
                $long = $result[1];

                $property->latitude =  $lat ?? null;
                $property->longitude =  $long ?? null;
                $property->save();
            }

            $property->category_id = $request->category_id;
            $property->destination_id = $request->destination_id;
            $property->type_id = $request->type_id;
            $property->style_id = $request->style_id;
            $property->title = $request->title;
            $property->slug = $request->slug;
            $property->description = $request->description;
            $property->address = $request->address;
            $property->zip_code = $request->zip_code;
            $property->country = $request->country;
            $property->city = $request->city;
            $property->state = $request->state;
            $property->save();

            $refundInfos = [];

            if ($request->has('refund_infos')) {
                foreach ($request->refund_infos as $rule) {
                    $refundInfos[] = [
                        'percentage' => $rule['percentage'] ?? null,
                        'days'       => $rule['days'] ?? null,
                        'message'    => $rule['message'] ?? '',
                    ];
                }
            }

            $property->pricing()->updateOrCreate(
                ['property_id' => $property->id],
                [
                    'nightly_rate' => $request->nightly_rate,
                    'weekly_rate' => $request->weekly_rate,
                    'monthly_rate' => $request->monthly_rate,
                    'cleaning_fee' => $request->cleaning_fee,
                    'service_fee' => $request->service_fee,
                    'refundable' => $request->refundable,
                    'refund_infos' => $refundInfos,
                ]
            );

            $property->availability()->updateOrCreate(
                ['property_id' => $property->id],
                [
                    'available_from' => $request->available_from,
                    'available_to' => $request->available_to ?? null,
                ]
            );

            $property->allAmenity()->updateOrCreate(
                ['property_id' => $property->id],
                [
                    'amenities' => $formattedAmenities,
                ]
            );

            $property->photos()->update([
                'images' => $formattedData
            ]);

        $others = $request->others;

        if (is_string($others)) {
            $others = json_decode($others, true);
        } elseif (!is_array($others)) {
            $others = [];
        }
            $property->features()->update([
                'bathrooms' => $request->bathrooms,
                'bedrooms' => $request->bedrooms,
                'max_guests' => $request->max_guests,
                'others' => $others
            ]);

            DB::commit();

            return back()->with("success", "Property updated successfully");
        } catch (\Exception $exception) {
            DB::rollBack();
            return back()->with("error", $exception->getMessage());
        }
    }


    public function action(Request $request)
    {
        DB::beginTransaction();
        try {
            $property = Property::findOrFail($request->property_id);
            $property->status = $request->status;
            $property->save();


            $title = $property->activityTitle;

            $admin = Auth::user();

            $activity = new ActivityLog();
            $activity->title = $title;
            $activity->property_id = $request->property_id;
            $activity->description = $request->comments;

            $admin->activities()->save($activity);
            DB::commit();

            $user = $property->host;
            $msg = [
                'username' => $user->username,
                'title' => $property->title,
                'status' => $title,
                'comments' => $request->comments

            ];
            $action = [
                "link" => route('service.details', $property->slug),
                "icon" => "fa fa-money-bill-alt text-white"
            ];
            $this->userPushNotification($user, 'PROPERTY_ACTION', $msg, $action);

            $this->sendMailSms($user, 'PROPERTY_ACTION', [
                'username' => $user->username,
                'title' => $property->title,
                'status' => $title,
                'comments' => $request->comments
            ]);

            return back()->with('success', 'Update Successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }

    }
    public function delete($id)
    {
        try {
            $property = Property::with([
                'photos',
                'features',
                'pricing',
                'availability',
                'allAmenity',
                'activites'
            ])->findOrFail($id);

            if ($property->photos) {
                $thumb = $property->photos->images['thumb'] ?? null;
                $images = $property->photos->images['images'] ?? [];

                if ($thumb) {
                    $this->fileDelete($thumb['driver'], $thumb['path']);
                }

                foreach ($images as $image) {
                    $this->fileDelete($image['driver'], $image['path']);
                }

                $property->photos->delete();
            }

            if ($property->features) {
                $property->features()->delete();
            }

            if ($property->pricing) {
                $property->pricing()->delete();
            }

            if ($property->availability) {
                $property->availability()->delete();
            }

            if ($property->allAmenity) {
                $property->allAmenity()->delete();
            }

            if ($property->activites) {
                $property->activites()->delete();
            }

            $property->delete();

            return back()->with('success', __('Property has been deleted'));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function deleteMultiple(Request $request)
    {
        if (!$request->has('strIds') || empty($request->strIds)) {
            session()->flash('error', 'You did not select any properties.');
            return response()->json(['error' => 1]);
        }

        $ids = is_array($request->strIds) ? $request->strIds : explode(',', $request->strIds);

        $properties = Property::with([
            'photos', 'features', 'pricing', 'availability', 'allAmenity', 'activites'
        ])->whereIn('id', $ids)
            ->get();

        if ($properties->isEmpty()) {
            session()->flash('error', 'No properties found to delete.');
            return response()->json(['error' => 1]);
        }

        DB::transaction(function () use ($properties) {
            foreach ($properties as $property) {
                $property->allAmenity()->delete();

                $property->availability()->delete();

                $property->features()->delete();

                $property->activites()->delete();

                $property->pricing()->delete();

                if ($property->photos && is_array($property->photos->images)) {
                    $thumb = $property->photos->images['thumb'] ?? null;
                    $gallery = $property->photos->images['images'] ?? [];

                    if ($thumb) {
                        $this->fileDelete($thumb['driver'], $thumb['path']);
                    }

                    foreach ($gallery as $image) {
                        $this->fileDelete($image['driver'], $image['path']);
                    }

                    $property->photos->delete();
                }

                $property->delete();
            }
        });

        session()->flash('success', 'Selected properties deleted successfully.');
        return response()->json(['success' => 1]);
    }

    public function propertySeo($id)
    {
        $data['property'] = Property::with('seo')->where('id', $id)->first();

        if (!$data['property']) {
            return back()->with('error', 'Property not found');
        }

        return view('admin.property.seo', $data);
    }

    public function propertySeoUpdate(Request $request)
    {
        $request->validate([
            'property_id' => 'required|exists:properties,id',
            'page_title' => 'required|string|min:3|max:100',
            'meta_title' => 'nullable|string|min:3|max:191',
            'meta_keywords' => 'nullable|array',
            'meta_keywords.*' => 'nullable|string|min:1|max:255',
            'meta_description' => 'nullable|string|min:1|max:500',
            'og_description' => 'nullable|string|min:1|max:500',
            'meta_robots' => 'nullable|array',
            'meta_robots.*' => 'nullable|string|min:1|max:255',
            'seo_meta_image' => 'nullable|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $seo = PropertySeo::where('property_id', $request->property_id)->first();

        if ($request->hasFile('seo_meta_image')) {
            $metaImage = $this->fileUpload(
                $request->seo_meta_image,
                config('filelocation.seo.path'),
                null,
                null,
                'webp',
                60,
                $seo->meta_image ?? null,
                $seo->meta_image_driver ?? null
            );

            throw_if(empty($metaImage['path']), 'Image path not found');
        }

        $meta_robots = $request->meta_robots ? implode(",", $request->meta_robots) : null;

        PropertySeo::updateOrCreate(
            ['property_id' => $request->property_id],
            [
                'page_title' => $request->page_title,
                'meta_title' => $request->meta_title,
                'meta_keywords' => $request->meta_keywords,
                'meta_description' => $request->meta_description,
                'og_description' => $request->og_description,
                'meta_robots' => $meta_robots,
                'meta_image' => $metaImage['path'] ?? ($seo->meta_image ?? null),
                'meta_image_driver' => $metaImage['driver'] ?? ($seo->meta_image_driver ?? null),
            ]
        );

        return back()->with('success', 'Property SEO updated successfully');
    }
}
