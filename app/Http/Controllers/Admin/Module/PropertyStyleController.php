<?php

namespace App\Http\Controllers\Admin\Module;

use App\Http\Controllers\Controller;
use App\Models\PropertyStyle;
use App\Models\PropertyType;
use App\Traits\Notify;
use App\Traits\Upload;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class PropertyStyleController extends Controller
{
    use Notify, Upload;

    public function list()
    {
        $currentMonth = now()->month;
        $currentYear = now()->year;

        $data['propertyStyleStats'] = PropertyStyle::selectRaw("
            COUNT(*) as total,
            SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END) as active,
            SUM(CASE WHEN status = 0 THEN 1 ELSE 0 END) as inactive,
            SUM(CASE WHEN MONTH(created_at) = ? AND YEAR(created_at) = ? THEN 1 ELSE 0 END) as this_month,
            SUM(CASE WHEN YEAR(created_at) = ? THEN 1 ELSE 0 END) as this_year
        ", [$currentMonth, $currentYear, $currentYear])->first();

        $total = $data['propertyStyleStats']->total ?: 1;

        $data['percentages'] = [
            'active' => round(($data['propertyStyleStats']->active / $total) * 100, 2),
            'inactive' => round(($data['propertyStyleStats']->inactive / $total) * 100, 2),
            'this_month' => round(($data['propertyStyleStats']->this_month / $total) * 100, 2),
            'this_year' => round(($data['propertyStyleStats']->this_year / $total) * 100, 2),
        ];

        return view('admin.property.style.list', $data);
    }

    public function listSearch(Request $request)
    {
        $search = $request->search['value'];
        $filterSearch = $request->filterSearch;
        $filterStatus = $request->filterStatus;
        $filterDate = explode('-', $request->filterDate);
        $startDate = $filterDate[0];
        $endDate = isset($filterDate[1]) ? trim($filterDate[1]) : null;

        $category = PropertyStyle::query()
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
                $editUrl = route('admin.propertyStyle.edit', $item->id);
                $deleteUrl = route('admin.propertyStyle.delete', $item->id);
                $statusUrl = route('admin.propertyStyle.status', $item->id);
                $propertyUrl = route('admin.all.property', ['style' => $item->id]);

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
    public function add(){
        return view('admin.property.style.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->except('_token'), [
            'name' => 'required|string|max:255|unique:property_styles,name',
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
                $processImage = $this->fileUpload($request->image, config('filelocation.property_style.path'), null, config('filelocation.property_style.size'), 'webp', 60);
                $image = $processImage['path'];
                $image_driver = $processImage['driver'];
            }

            $type = new PropertyStyle();
            $type->name = $validatedData['name'];
            $type->status = $validatedData['status'];
            $type->description = $validatedData['description'];
            $type->image = $image ?? null;
            $type->driver = $image_driver ?? null;
            $type->save();

            return back()->with('success', 'Property Style added successfully');
        }catch (\Exception $exception){
            return back()->with('error', $exception->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $data['style'] = PropertyStyle::where('id', $id)->firstOr(function () {
                throw new \Exception('This Property Style is not available now');
            });

            return view('admin.property.style.edit', $data);
        }catch (\Exception $exception){
            return back()->with('error', $exception->getMessage());
        }
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->except('_token'), [
            'style_id' => 'required|exists:property_styles,id',
            'name' => 'required|string|max:255|unique:property_styles,name,' . $request->style_id,
            'status' => 'required|in:0,1',
            'description' => 'nullable|string|max:1000',
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:10240'],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $validatedData = $validator->validated();

        try {
            $style = PropertyStyle::where('id', $request->style_id)->firstOr(function () {
                throw new \Exception('This Property Style is not available now');
            });
            if ($request->hasFile('image')) {
                $processImage = $this->fileUpload($request->image, config('filelocation.property_style.path'), null, config('filelocation.property_style.size'), 'webp', 60, $style->image, $style->driver);
                $image = $processImage['path'];
                $image_driver = $processImage['driver'];
            }

            $style->name = $validatedData['name'];
            $style->status = $validatedData['status'];
            $style->description = $validatedData['description'];
            $style->image = $image ?? $style->image ?? null;
            $style->driver = $image_driver ?? $style->driver ?? null;
            $style->save();

            return back()->with('success', 'Property Style updated successfully');
        }catch (\Exception $exception){
            return back()->with('error', $exception->getMessage());
        }
    }

    public function status($id){
        try {
            $style = PropertyStyle::select('id', 'status')
                ->where('id', $id)
                ->firstOr(function () {
                    throw new \Exception('Property Style not found.');
                });

            $style->status = $style->status == 1 ? 0 : 1;
            $style->save();

            return back()->with('success','Property Style Status Changed Successfully.');
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

        PropertyStyle::select(['id', 'status'])->whereIn('id', $request->strIds)->get()->each(function ($style) {
            $style->status = ($style->status == 0) ? 1 : 0;
            $style->save();
        });

        session()->flash('success', 'Property Status status changed successfully');

        return response()->json(['success' => 1]);
    }

    public function deleteMultiple(Request $request)
    {
        if (!$request->has('strIds') || empty($request->strIds)) {
            session()->flash('error', 'You did not select any data.');
            return response()->json(['error' => 1]);
        }

        $ids = is_array($request->strIds) ? $request->strIds : explode(',', $request->strIds);
        $styles = PropertyStyle::with('properties')->whereIn('id', $ids)->get();

        foreach ($styles as $style) {
            if ($style->properties->isNotEmpty() ) {
                session()->flash('error', 'One or more selected types have related property and cannot be deleted.');
                return response()->json(['error' => 1]);
            }
        }

        $styles->each(function ($style) {
            $this->fileDelete($style->driver, $style->image);
            $style->delete();
        });


        session()->flash('success', 'Selected Styles deleted successfully.');
        return response()->json(['success' => 1]);
    }

    public function delete($id)
    {
        try {
            $style = PropertyStyle::with('properties')->where('id', $id)->firstOr(function () {
                throw new \Exception('This Style is not available now');
            });

            if ($style->properties->isNotEmpty()) {
                return back()->with('error', 'Selected Style has related properties and cannot be deleted.');
            }

            $this->fileDelete($style->driver, $style->image);
            $style->delete();

            return back()->with('success', 'Style deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

}
