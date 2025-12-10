<?php

namespace App\Http\Controllers\Admin\Module;

use App\Http\Controllers\Controller;
use App\Models\PropertyType;
use App\Traits\Notify;
use App\Traits\Upload;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class PropertyTypeController extends Controller
{
    use Notify, Upload;
    public function list()
    {
        $currentMonth = now()->month;
        $currentYear = now()->year;

        $data['propertyTypeStats'] = PropertyType::selectRaw("
            COUNT(*) as total,
            SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END) as active,
            SUM(CASE WHEN status = 0 THEN 1 ELSE 0 END) as inactive,
            SUM(CASE WHEN MONTH(created_at) = ? AND YEAR(created_at) = ? THEN 1 ELSE 0 END) as this_month,
            SUM(CASE WHEN YEAR(created_at) = ? THEN 1 ELSE 0 END) as this_year
        ", [$currentMonth, $currentYear, $currentYear])->first();

        $total = $data['propertyTypeStats']->total ?: 1;

        $data['percentages'] = [
            'active' => round(($data['propertyTypeStats']->active / $total) * 100, 2),
            'inactive' => round(($data['propertyTypeStats']->inactive / $total) * 100, 2),
            'this_month' => round(($data['propertyTypeStats']->this_month / $total) * 100, 2),
            'this_year' => round(($data['propertyTypeStats']->this_year / $total) * 100, 2),
        ];

        return view('admin.property.type.list', $data);
    }

    public function listSearch(Request $request)
    {
        $search = $request->search['value'];
        $filterSearch = $request->filterSearch;
        $filterStatus = $request->filterStatus;
        $filterDate = explode('-', $request->filterDate);
        $startDate = $filterDate[0];
        $endDate = isset($filterDate[1]) ? trim($filterDate[1]) : null;

        $category = PropertyType::query()
            ->withCount('properties')
            ->orderBy('id', 'DESC')
            ->when(!empty($search), function ($query) use ($search) {
                return $query->where('name', 'LIKE', "%{$search}%");
            })
            ->when(isset($filterSearch) && !empty($filterSearch), function ($query) use ($filterSearch) {
                return $query->where('name', 'LIKE', "%{$filterSearch}%");
            })
            ->when(isset($filterStatus), function ($query) use ($filterStatus) {
                if ($filterStatus == 'all') {
                    return $query->where('status', '!=', null);
                }
                return $query->where('status','=', $filterStatus);
            })
            ->when(!empty($request->filterDate) && $endDate != null, function ($query) use ($startDate, $endDate) {
                $startDate = Carbon::createFromFormat('d/m/Y', trim($startDate));
                $endDate = Carbon::createFromFormat('d/m/Y', trim($endDate));
                $query->whereBetween('created_at', [$startDate, $endDate]);
            });

        return DataTables::of($category)

            ->addColumn('checkbox', function ($item) {
                return ' <input type="checkbox" id="chk-' . $item->id . '"
                                       class="form-check-input row-tic tic-check" name="check" value="' . $item->id . '"
                                       data-id="' . $item->id . '">';
            })
            ->addColumn('name', function ($item) {
                $image = $item->image;
                if (!$image) {
                    $firstLetter = substr($item->name, 0, 1);
                    return '<div class="d-flex align-items-center">
                        <div class="avatar avatar-sm avatar-soft-primary avatar-circle">
                            <span class="avatar-initials">' . $firstLetter . '</span>
                        </div>
                        <span class="fs-6 text-body ps-2">' . $item->name . '</span>
                    </div>';
                } else {
                    $url = getFile($item->driver, $item->image);
                    return '<div class="d-flex align-items-center">
                    <div class="avatar avatar-sm avatar-circle">
                        <img class="avatar-img" src="' . $url . '" alt="'. $item->name .'" />
                    </div>
                    <span class="fs-6 text-body ps-2">' . $item->name . '</span>
                </div>';
                }
            })
            ->addColumn('description', function ($item) {
                $fullText = strip_tags($item->description);
                $shortText = mb_substr($fullText, 0, 50) . '...';

                return '<div class="description-container">
                    <span class="text-muted short-text">' . $shortText . '</span>
                    <div class="full-text d-none">' . $fullText . '</div>
                    <a href="#" class="see-more">See more</a>
                </div>';
            })
            ->addColumn('property', function ($item) {
                return ' <span class="badge bg-soft-primary text-dark">' . $item->properties_count . '</span>';
            })
            ->addColumn('status', function ($item) {
                if ($item->status == 1) {
                    return '<span class="badge bg-soft-success text-success">
                                <span class="legend-indicator bg-success"></span>' . trans('Active') . '
                              </span>';
                } elseif ($item->status == 0) {
                    return '<span class="badge bg-soft-danger text-danger">
                                <span class="legend-indicator bg-danger"></span>' . trans('Inactive') . '
                              </span>';
                } else {
                    return '<span class="badge bg-soft-secondary text-secondary">
                                <span class="legend-indicator bg-secondary"></span>' . trans('Unknown') . '
                              </span>';
                }
            })
            ->addColumn('created_at', function ($item) {
                return dateTime($item->created_at);
            })
            ->addColumn('action', function ($item) {
                $editUrl = route('admin.propertyType.edit', $item->id);
                $deleteUrl = route('admin.propertyType.delete', $item->id);
                $statusUrl = route('admin.propertyType.status', $item->id);
                $propertyUrl = route('admin.all.property', ['type' => $item->id]);
                return '<div class="btn-group" role="group">
                      <a href="' . $editUrl . '" class="btn btn-white btn-sm edit_user_btn">
                        <i class="bi-pencil-square me-1"></i> ' . trans("Edit") . '
                      </a>
                    <div class="btn-group">
                      <button type="button" class="btn btn-white btn-icon btn-sm dropdown-toggle dropdown-toggle-empty" id="userEditDropdown" data-bs-toggle="dropdown" aria-expanded="false"></button>
                      <div class="dropdown-menu dropdown-menu-end mt-1" aria-labelledby="userEditDropdown">
                         <a class="dropdown-item statusBtn" href="javascript:void(0)"
                           data-route="'. $statusUrl .'"
                           data-bs-toggle="modal" data-bs-target="#statusModal">
                            <i class="bi bi-check-circle dropdown-item-icon"></i>
                            '. trans("Status Change") .'
                        </a>
                        <a class="dropdown-item" href="'. $propertyUrl .'">
                            <i class="bi bi-p-circle dropdown-item-icon"></i>
                            '. trans("Properties") .'
                        </a>
                        <a class="dropdown-item deleteSingleBtn text-danger" href="javascript:void(0)"
                           data-route="'. $deleteUrl .'"
                           data-bs-toggle="modal" data-bs-target="#deleteModal">
                            <i class="bi bi-trash dropdown-item-icon text-danger"></i>
                            '. trans("Delete") .'
                        </a>
                      </div>
                    </div>
                  </div>';
            })->rawColumns(['action', 'checkbox', 'name','property', 'status', 'created_at', 'description'])
            ->make(true);
    }

    public function add()
    {
        return view('admin.property.type.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->except('_token'), [
            'name' => 'required|string|max:255|unique:property_types,name',
            'status' => 'required|in:0,1',
            'description' => 'nullable|string|max:1000',
            'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:10240',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $validatedData = $validator->validated();

        try {
            if ($request->hasFile('image')) {
                $processImage = $this->fileUpload($request->image, config('filelocation.property_type.path'), null, null, 'webp', 60);
                $image = $processImage['path'];
                $image_driver = $processImage['driver'];
            }

            $type = new PropertyType();
            $type->name = $validatedData['name'];
            $type->status = $validatedData['status'];
            $type->description = $validatedData['description'];
            $type->image = $image ?? null;
            $type->driver = $image_driver ?? null;
            $type->save();

            return back()->with('success', 'Property Type added successfully');
        }catch (\Exception $exception){
            return back()->with('error', $exception->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $data['type'] = PropertyType::where('id', $id)->firstOr(function () {
                throw new \Exception('This Property Type is not available now');
            });
            return view('admin.property.type.edit', $data);
        }catch (\Exception $exception){
            return back()->with('error', $exception->getMessage());
        }
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->except('_token'), [
            'type_id' => 'required|exists:property_types,id',
            'name' => 'required|string|max:255|unique:property_types,name,' . $request->type_id,
            'status' => 'required|in:0,1',
            'description' => 'nullable|string|max:1000',
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:10240'],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $validatedData = $validator->validated();

        try {
            $type = PropertyType::where('id', $request->type_id)->firstOr(function () {
                throw new \Exception('This Property Type is not available now');
            });
            if ($request->hasFile('image')) {
                $processImage = $this->fileUpload($request->image, config('filelocation.property_type.path'), null, null, 'webp', 60, $type->image, $type->driver);
                $image = $processImage['path'];
                $image_driver = $processImage['driver'];
            }

            $type->name = $validatedData['name'];
            $type->status = $validatedData['status'];
            $type->description = $validatedData['description'];
            $type->image = $image ?? $type->image ?? null;
            $type->driver = $image_driver ?? $type->driver ?? null;
            $type->save();

            return back()->with('success', 'Property Type updated successfully');
        }catch (\Exception $exception){
            return back()->with('error', $exception->getMessage());
        }
    }

    public function status($id){
        try {
            $type = PropertyType::select('id', 'status')
                ->where('id', $id)
                ->firstOr(function () {
                    throw new \Exception('Property Type not found.');
                });

            $type->status = $type->status == 1 ? 0 : 1;
            $type->save();

            return back()->with('success','Property Type Status Changed Successfully.');
        }catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function statusMultiple(Request $request)
    {
        if (!$request->has('strIds') || empty($request->strIds)) {
            session()->flash('error', 'You did not select any data.');
            return response()->json(['error' => 1]);
        }

        PropertyType::select(['id', 'status'])->whereIn('id', $request->strIds)->get()->each(function ($type) {
            $type->status = ($type->status == 0) ? 1 : 0;
            $type->save();
        });

        session()->flash('success', 'Property Type status changed successfully');

        return response()->json(['success' => 1]);
    }

    public function deleteMultiple(Request $request)
    {
        if (!$request->has('strIds') || empty($request->strIds)) {
            session()->flash('error', 'You did not select any data.');
            return response()->json(['error' => 1]);
        }

        $ids = is_array($request->strIds) ? $request->strIds : explode(',', $request->strIds);
        $types = PropertyType::with('properties')->whereIn('id', $ids)->get();

        foreach ($types as $type) {
            if ($type->properties->isNotEmpty() ) {
                session()->flash('error', 'One or more selected types have related property and cannot be deleted.');
                return response()->json(['error' => 1]);
            }
        }

        $types->each(function ($type) {
            $this->fileDelete($type->driver, $type->image);
            $type->delete();
        });


        session()->flash('success', 'Selected Types deleted successfully.');
        return response()->json(['success' => 1]);
    }

    public function delete($id)
    {
        try {
            $type = PropertyType::with('properties')->where('id', $id)->firstOr(function () {
                throw new \Exception('This Types is not available now');
            });

            if ($type->properties->isNotEmpty()) {
                return back()->with('error', 'Selected Types has related properties and cannot be deleted.');
            }
            $this->fileDelete($type->driver, $type->image);
            $type->delete();

            return back()->with('success', 'Types deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
