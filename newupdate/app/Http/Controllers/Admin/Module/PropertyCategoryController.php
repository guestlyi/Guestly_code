<?php

namespace App\Http\Controllers\Admin\Module;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\PropertyCategory;
use App\Traits\Notify;
use App\Traits\Upload;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class PropertyCategoryController extends Controller
{
    use Notify, Upload;

    public function list()
    {
        $currentMonth = now()->month;
        $currentYear = now()->year;

        $data['categoryStats'] = PropertyCategory::selectRaw("
            COUNT(*) as total,
            SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END) as active,
            SUM(CASE WHEN status = 0 THEN 1 ELSE 0 END) as inactive,
            SUM(CASE WHEN MONTH(created_at) = ? AND YEAR(created_at) = ? THEN 1 ELSE 0 END) as this_month,
            SUM(CASE WHEN YEAR(created_at) = ? THEN 1 ELSE 0 END) as this_year
        ", [$currentMonth, $currentYear, $currentYear])->first();

        $total = $data['categoryStats']->total ?: 1;

        $data['percentages'] = [
            'active' => round(($data['categoryStats']->active / $total) * 100, 2),
            'inactive' => round(($data['categoryStats']->inactive / $total) * 100, 2),
            'this_month' => round(($data['categoryStats']->this_month / $total) * 100, 2),
            'this_year' => round(($data['categoryStats']->this_year / $total) * 100, 2),
        ];
        return view('admin.property.category.list', $data);
    }
    public function listSearch(Request $request)
    {
        $search = $request->search['value'];
        $filterSearch = $request->filterSearch;
        $filterStatus = $request->filterStatus;
        $filterDate = explode('-', $request->filterDate);
        $startDate = $filterDate[0];
        $endDate = isset($filterDate[1]) ? trim($filterDate[1]) : null;

        $category = PropertyCategory::query()
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
                    return '<div class="avatar avatar-sm avatar-soft-primary avatar-circle">
                                <span class="avatar- initials">' . $firstLetter . '</span>
                                <span class="fs-6 text-body ps-1">' . $item->name . '</span>
                            </div>';

                } else {
                    $url = getFile($item->image_driver, $item->image);
                    return '<div class="avatar avatar-sm avatar-circle">
                                <img class="avatar-img" src="' . $url . '" alt="Service Thumb Image" />
                                <span class="fs-6 text-body ps-1">' . $item->name . '</span>
                            </div>
                            ';

                }
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
                    return '<span class="badge bg-soft-danger text-danger">
                                <span class="legend-indicator bg-danger"></span>' . trans('Unknown') . '
                              </span>';
                }
            })
            ->addColumn('created_at', function ($item) {
                return dateTime($item->created_at);

            })
            ->addColumn('action', function ($item) {
                $editUrl = route('admin.property.categoryEdit', $item->id);
                $deleteUrl = route('admin.property.categoryDelete', $item->id);
                $statusUrl = route('admin.property.categoryStatus', $item->id);
                $propertyRoute = route('admin.all.property', ['category' => $item->id]);
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
                        <a class="dropdown-item" href="'. $propertyRoute .'">
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
            })->rawColumns(['action', 'checkbox', 'name','property', 'status', 'created_at'])
            ->make(true);
    }
    public function add(){
        return view('admin.property.category.create');
    }

    public function store(Request $request){
        $this->validate($request,[
            'name' => 'required',
            'image' => 'required|max:3072|image|mimes:jpg,jpeg,png',
        ]);
        try {
            if ($request->hasFile('image')) {
                $imageData = $this->fileUpload($request->image, config('filelocation.property_category.path'), null, config('filelocation.property_category.size'), 'webp', '60');

                if (empty($imageData) || !isset($imageData['path'], $imageData['driver'])) {
                    throw new \Exception('Image upload failed: Missing required data.');
                }
            }

            $category = new PropertyCategory();
            $category->name = $request->input('name');
            $category->slug = slug($request->input('name'));
            $category->status  = $request->input('status');
            $category->image  = $imageData['path'];
            $category->image_driver  = $imageData['driver'];
            $category->save();

            return back()->with('success','Category added successfully');
        }catch (\Exception $exception){
            return back()->withErrors($exception->getMessage());
        }
    }

    public function edit($id){

        $data['category'] = PropertyCategory::where('id', $id)->first();

        return view('admin.property.category.edit', $data);
    }

    public function update(Request $request)
    {
        $this->validate($request,[
            'name' => 'required',
            'image' => 'nullable|max:3072|image|mimes:jpg,jpeg,png',
        ]);

        try {
            $category = PropertyCategory::where('id', $request->category_id)->firstOr(function () {
                throw new \Exception('Category not found.');
            });

            if ($request->hasFile('image')) {
                $imageData = $this->fileUpload($request->image, config('filelocation.property_category.path'), null, config('filelocation.property_category.size'), 'webp', '60', $category->image, $category->image_driver);

                if (empty($imageData) || !isset($imageData['path'], $imageData['driver'])) {
                    throw new \Exception('Image upload failed: Missing required data.');
                }

                $category->image  = $imageData['path'];
                $category->image_driver  = $imageData['driver'];
                $category->save();
            }

            $category->name = $request->input('name');
            $category->slug = slug($request->input('name'));
            $category->status  = $request->input('status');
            $category->save();

            return back()->with('success','Category Updated successfully');
        }catch (\Exception $exception){
            return back()->with('error',$exception->getMessage());
        }
    }

    public function status($id){
        try {
            $category = PropertyCategory::select('id', 'status')
                ->where('id', $id)
                ->firstOr(function () {
                    throw new \Exception('Category not found.');
                });

            $category->status = ($category->status == 1) ? 0 : 1;
            $category->save();

            return back()->with('success','Category Status Changed Successfully.');
        }catch (\Exception $e){
            return back()->with('error', $e->getMessage());
        }
    }
    public function statusMultiple(Request $request)
    {
        if (!$request->has('strIds') || empty($request->strIds)) {
            session()->flash('error', 'You did not select any data.');
            return response()->json(['error' => 1]);
        }

        PropertyCategory::select(['id', 'status'])->whereIn('id', $request->strIds)->get()->each(function ($category) {
            $category->status = ($category->status == 0) ? 1 : 0;
            $category->save();
        });

        session()->flash('success', 'All selected categories status changed successfully');

        return response()->json(['success' => 1]);
    }
    public function delete($id){
        try {
            $category = PropertyCategory::with('properties')
                ->where('id', $id)
                ->firstOr(function () {
                    throw new \Exception('Category not found.');
                });

            if ($category->properties->count() > 0) {
                return back()->with('error', 'Category cannot be deleted because it is associated with properties.');
            }

            $this->fileDelete($category->image_driver, $category->image);
            $category->delete();

            return back()->with('success','Category Deleted Successfully.');
        }catch (\Exception $e){
            return back()->with('error', $e->getMessage());
        }
    }

    public function deleteMultiple(Request $request)
    {
        if (!$request->has('strIds') || empty($request->strIds)) {
            session()->flash('error', 'You did not select any data.');
            return response()->json(['error' => 1]);
        }

        $ids = is_array($request->strIds) ? $request->strIds : explode(',', $request->strIds);

        $categories = PropertyCategory::with('properties')->whereIn('id', $ids)->get();

        if ($categories->pluck('properties')->flatten()->count() > 0) {
            session()->flash('error', 'One or more selected Categories have related properties and cannot be deleted.');
            return response()->json(['error' => 1]);
        }

        DB::transaction(function () use ($categories) {
            foreach ($categories as $category) {
                $this->fileDelete($category->image_driver, $category->image);
                $category->delete();
            }
        });

        session()->flash('success', 'Selected categories deleted successfully.');
        return response()->json(['success' => 1]);
    }
}
