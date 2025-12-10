<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Badge;
use App\Models\User;
use App\Models\UserBadge;
use App\Traits\Notify;
use App\Traits\Upload;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class BadgeController extends Controller
{
    use Notify, Upload;

    public function list()
    {
        try {
            $currentMonth = now()->startOfMonth();
            $nextMonth = now()->startOfMonth()->addMonth();
            $currentYearStart = now()->startOfYear();
            $nextYearStart = now()->startOfYear()->addYear();

            $badgeStats = Badge::selectRaw(
                'COUNT(*) as totalBadges,
                 SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END) as totalActiveBadges,
                 SUM(CASE WHEN status = 0 THEN 1 ELSE 0 END) as totalInactiveBadges,
                 SUM(CASE WHEN created_at >= ? AND created_at < ? THEN 1 ELSE 0 END) as totalBadgesThisMonth,
                 SUM(CASE WHEN created_at >= ? AND created_at < ? THEN 1 ELSE 0 END) as totalBadgesThisYear',
                [$currentMonth, $nextMonth, $currentYearStart, $nextYearStart]
            )->first();

            $totalBadges = $badgeStats->totalBadges ?? 0;

            $data['totalBadges'] = $totalBadges;
            $data['totalActiveBadges'] = $badgeStats->totalActiveBadges ?? 0;
            $data['totalInactiveBadges'] = $badgeStats->totalInactiveBadges ?? 0;
            $data['totalBadgesThisMonth'] = $badgeStats->totalBadgesThisMonth ?? 0;
            $data['totalBadgesThisYear'] = $badgeStats->totalBadgesThisYear ?? 0;

            $data['activeBadgePercentage'] = $totalBadges > 0 ? ($data['totalActiveBadges'] / $totalBadges) * 100 : 0;
            $data['inactiveBadgePercentage'] = $totalBadges > 0 ? ($data['totalInactiveBadges'] / $totalBadges) * 100 : 0;
            $data['thisMonthBadgePercentage'] = $totalBadges > 0 ? ($data['totalBadgesThisMonth'] / $totalBadges) * 100 : 0;
            $data['thisYearBadgePercentage'] = $totalBadges > 0 ? ($data['totalBadgesThisYear'] / $totalBadges) * 100 : 0;

            $data['badge'] = Badge::orderBy('sort_by', 'ASC')->get();

            return view('admin.badges.list', $data);
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function search(Request $request)
    {

        $search = $request->input('search.value');
        $filterName = $request->input('filterName');
        $filterDate = explode('-', $request->filterDate);
        $startDate = $filterDate[0];
        $endDate = isset($filterDate[1]) ? trim($filterDate[1]) : null;
        $filterStatus = $request->input('filterStatus');

        $badge = Badge::query()
            ->withCount('vendorInfos')
            ->orderBy('sort_by', 'asc')
            ->when(isset($search) && !empty($search), function ($query) use ($search) {
                return $query->where('title', 'LIKE', "%{$search}%");
            })
            ->when(isset($filterName) && !empty($filterName), function ($query) use ($filterName) {
                return $query->where('title', 'LIKE', "%{$filterName}%");
            })
            ->when(isset($filterStatus), function ($query) use ($filterStatus) {
                if ($filterStatus == 'all') {
                    $query->whereNotNull('status');
                } elseif ($filterStatus == 0) {
                    $query->where('status', 0);
                } elseif ($filterStatus == 1) {
                    $query->where('status', 1);
                }
            })
            ->when(!empty($request->filterDate) && $endDate != null, function ($query) use ($startDate, $endDate) {
                $startDate = Carbon::createFromFormat('d/m/Y', trim($startDate));
                $endDate = Carbon::createFromFormat('d/m/Y', trim($endDate));
                $query->whereBetween('created_at', [$startDate, $endDate]);
            });

        return DataTables::of($badge)
            ->addColumn('checkbox', function ($item) {
                return ' <input type="checkbox" id="chk-' . $item->id . '"
                                       class="form-check-input row-tic tic-check" name="check" value="' . $item->id . '"
                                       data-id="' . $item->id . '">';
            })
            ->addColumn('badge', function ($item) {
                $editUrl = '#';
                $image = $item->icon;
                $shortened= substr($item->title, 0, 30);
                if (!$image) {
                    $firstLetter = substr($item->title, 0, 1);
                    return '<div class="avatar avatar-sm avatar-soft-primary avatar-circle d-flex justify-content-start gap-2 w-100">
                                <span class="avatar-initials">' . $firstLetter . '</span><a href="'. $editUrl .'">
                                <p class="avatar-initials ms-3">' . $shortened . '</p></a>
                            </div>';

                } else {
                    $url = getFile($item->driver, $item->icon);
                    return '<a class="d-flex align-items-center me-2" href="'. $editUrl .'">
                                <div class="flex-shrink-0">
                                  <div class="avatar avatar-sm avatar-circle">
                                        <img class="avatar-img adsImage" src="' . $url . '" alt="Image Description">
                                  </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                  <h5 class="text-hover-primary mb-0">' . $shortened . '</h5>
                                </div>
                              </a>';

                }
            })
            ->addColumn('description', function ($item) {
                $short = Str::limit(strip_tags($item->description), 30, '...');

                return '<span class="description-popover"
                    data-bs-toggle="popover"
                    data-bs-html="true"
                    data-bs-placement="right">' . $short . '</span>';
            })
            ->addColumn('user', function ($item) {
                return ' <span class="badge bg-soft-secondary text-dark">' . $item->vendor_infos_count . '</span>';
            })
            ->addColumn('status', function ($item) {
                if ($item->status == 0 ) {
                    return ' <span class="badge bg-soft-warning text-warning">
                                <span class="legend-indicator bg-warning"></span> ' . trans('Pending') . '
                             </span>';
                }elseif ($item->status == 1) {
                    return ' <span class="badge bg-soft-success text-success">
                                <span class="legend-indicator bg-success"></span> ' . trans('Active') . '
                             </span>';
                } elseif($item->status == 2) {
                    return '<span class="badge bg-soft-danger text-danger">
                                <span class="legend-indicator bg-danger"></span> ' . trans('Expired') . '
                            </span>';
                }
            })
            ->addColumn('create-at', function ($item) {
                return dateTime($item->created_at);
            })
            ->addColumn('action', function ($item) {
                $editUrl = route('admin.badge.edit', $item->id);

                return '<div class="btn-group sortable" role="group" data-code="'.$item->id.'">
                  <a href="' . $editUrl . '" class="btn btn-white btn-sm edit_user_btn">
                    <i class="bi-pencil-square me-1"></i> ' . trans("Edit") . '
                  </a>
                  <div class="btn-group">
                    <button type="button" class="btn btn-white btn-icon btn-sm dropdown-toggle dropdown-toggle-empty" id="userEditDropdown" data-bs-toggle="dropdown" aria-expanded="false"></button>
                    <div class="dropdown-menu dropdown-menu-end mt-1" aria-labelledby="userEditDropdown">
                      <a class="dropdown-item deleteBtn" href="javascript:void(0)"
                       data-route="' . route("admin.badge.delete", $item->id) . '"
                       data-bs-toggle="modal" data-bs-target="#deleteModal">
                        <i class="bi bi-trash"></i> ' . trans("Delete") . '
                      </a>
                    </div>
                  </div>
                </div>';
            })->rawColumns(['action', 'checkbox','create-at', 'badge', 'description', 'status','user'])
            ->make(true);
    }

    public function create(){
        return view('admin.badges.add');
    }
    public function store(Request $request){
        $rules = [
            'title' => 'required|string|max:255',
            'description' => 'required',
            'icon' => 'required|mimes:jpeg,png,jpg|max:1024',
        ];
        $request->validate($rules);

        try {
            if ($request->hasFile('icon')) {
                $photo = $this->fileUpload($request->icon, config('filelocation.badge.path'), null, null, 'webp', 50);
                $icon = $photo['path'];
                $driver = $photo['driver'];
            }

            $badge = new Badge();
            $badge->title = $request->title;
            $badge->description = $request->description;
            $badge->status = $request->status;
            $badge->icon = $icon;
            $badge->driver = $driver;
            $badge->save();

            throw_if(!$badge, 'Something is wrong, Please try again.');

            return back()->with('success', 'Badge Created Successfully.');
        }catch (\Exception $exception){
            return back()->with('error', $exception->getMessage());
        }
    }

    public function edit($id){
        try {
            $data['badge'] = Badge::where('id', $id)->firstOr(function () {
                throw new \Exception('Badge not found');
            });

            return view('admin.badges.edit', $data);
        }catch (\Exception $e){
            return back()->with('error', $e->getMessage());
        }
    }

    public function update(Request $request){
        $rules = [
            'title' => 'required|string|max:255',
            'description' => 'required|string|min:5',
            'status' => 'required|boolean',
        ];

        if ($request->hasFile('icon')) {
            $rules['icon'] = 'required|mimes:jpeg,png,jpg|max:1024';
        }

        $request->validate($rules);

        try {
            $badge = Badge::where('id', $request->badge)->firstOr(function () {
                throw new \Exception('Badge not found');
            });

            if ($request->hasFile('icon')) {
                $photo = $this->fileUpload($request->icon, config('filelocation.badge.path'), null, null, 'webp', 50, $badge->icon, $badge->icon_driver);
                $icon = $photo['path'];
                $driver = $photo['driver'];

                $badge->icon = $icon;
                $badge->driver = $driver;
                $badge->save();
            }

            $badge->title = $request->title;
            $badge->description = $request->description;
            $badge->status = $request->status;
            $badge->save();

            throw_if(!$badge, 'Something is wrong, Please try again.');

            return back()->with('success', 'Badge Updated Successfully.');
        }catch (\Exception $exception){
            return back()->with('error', $exception->getMessage());
        }
    }
    public function deleteMultiple(Request $request)
    {
        if (empty($request->strIds)) {
            return response()->json(['error' => 1, 'message' => 'No badges selected.']);
        }

        $badges = Badge::whereIn('id', $request->strIds)->get();

        foreach ($badges as $badge) {
            if (!empty($badge->users)) {
                return response()->json(['error' => 1, 'message' => 'One or more badges are assigned to users.']);
            }

            $this->fileDelete($badge->icon_driver, $badge->icon);
            $badge->delete();
        }

        return response()->json(['success' => 1, 'message' => 'Selected badges deleted successfully.']);
    }
    public function statusMultiple(Request $request)
    {
        if ($request->strIds == null) {
            session()->flash('error', 'You have not selected any data.');
            return response()->json(['error' => 1]);
        }

        $categories = Badge::whereIn('id', $request->strIds)->get();

        foreach ($categories as $category) {
            $category->status = ($category->status == 1) ? 0 : 1;
            $category->save();
        }

        session()->flash('success', 'Selected Badge Status Changed Successfully.');
        return response()->json(['success' => 1]);
    }

    public function delete($id){
        try {
            $badge = Badge::where('id', $id)->firstOr(function () {
                throw new \Exception('Badge not found');
            });

            if (empty($badge->users)) {
                $this->fileDelete($badge->icon_driver, $badge->icon);
                $badge->delete();

                return back()->with('success', 'Badge Deleted Successfully.');
            }

            return back()->with('error', 'This badge is assigned to a user. Remove the user first.');
        }catch (\Exception $e){
            return back()->with('error', $e->getMessage());
        }
    }
    public function badgeSort(Request $request)
    {
        $sortItems = $request->sort;
        foreach ($sortItems as $key => $value) {
            Badge::where('id', $value)->update(['sort_by' => $key + 1]);
        }

        return response()->json(['message' => 'Badges sorted successfully'], 200);
    }
}
