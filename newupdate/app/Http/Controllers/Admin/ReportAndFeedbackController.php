<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Amenity;
use App\Models\Feedback;
use App\Models\Property;
use App\Models\Report;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class ReportAndFeedbackController extends Controller
{
    public function report(Request $request)
    {
        $currentMonth = now()->month;
        $currentYear = now()->year;

        $data['reportStats'] = Report::selectRaw("
            COUNT(*) as total,
            SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END) as active,
            SUM(CASE WHEN status = 0 THEN 1 ELSE 0 END) as inactive,
            SUM(CASE WHEN MONTH(created_at) = ? AND YEAR(created_at) = ? THEN 1 ELSE 0 END) as this_month,
            SUM(CASE WHEN YEAR(created_at) = ? THEN 1 ELSE 0 END) as this_year
        ", [$currentMonth, $currentYear, $currentYear])->first();

        $total = $data['reportStats']->total ?? 1;

        $data['percentages'] = [
            'active' => $total ? round(($data['reportStats']->active / $total) * 100, 2):0,
            'inactive' => $total ? round(($data['reportStats']->inactive / $total) * 100, 2):0,
            'this_month' => $total ? round(($data['reportStats']->this_month / $total) * 100, 2):0,
            'this_year' => $total? round(($data['reportStats']->this_year / $total) * 100, 2):0,
        ];

        return view('admin.reportAndFeedback.report', $data);
    }

    public function reportSearch(Request $request)
    {
        $search = $request->search['value'];
        $filterSearch = $request->filterSearch;
        $filterStatus = $request->filterStatus;
        $filterDate = explode('-', $request->filterDate);
        $startDate = $filterDate[0];
        $endDate = isset($filterDate[1]) ? trim($filterDate[1]) : null;

        $category = Report::query()
            ->with(['property.photos'])
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
                return $query->where('status', '=', $filterStatus);
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
                return $item->name;
            })
            ->addColumn('property', function ($item) {
                $property = optional($item->property);
                $image = optional($property->photos)->images['thumb'] ?? null;
                $firstLetter = substr($property->title ?? '', 0, 1);
                $titleHtml = '<span class="fs-6 text-body ps-1">' . e($property->title) . '</span>';

                if (!$image) {
                    return '<div class="d-flex align-items-center">
                        <div class="avatar avatar-sm avatar-soft-primary avatar-circle">
                            <span class="avatar-initials">' . e($firstLetter) . '</span>
                        </div>' . $titleHtml . '</div>';
                }

                $url = getFile($image['driver'], $image['path']);
                return '<div class="d-flex align-items-center">
                    <div class="avatar avatar-sm avatar-circle">
                        <img class="avatar-img" src="' . e($url) . '" alt="Property Image" style="width:32px; height:32px; object-fit:cover;">
                    </div>' . $titleHtml . '</div>';
            })
            ->addColumn('report', function ($item) {
                $plainText = strip_tags($item->report);
                $shortText = strlen($plainText) > 50 ? substr($plainText, 0, 50) . '...' : $plainText;

                return '
                    <span class="short-report">' . e($shortText) . '</span>
                    <a href="javascript:void(0);" class="see-more-btn" data-fullreport="' . e($item->report) . '">See more</a>
                ';
            })
            ->addColumn('reported_at', function ($item) {
                return dateTime($item->created_at);
            })
            ->addColumn('status', function ($item) {
                if ($item->status == 1) {
                    return '<span class="badge bg-soft-success text-success">
                                <span class="legend-indicator bg-success"></span>' . trans('Active') . '
                              </span>';
                } elseif ($item->status == 0) {
                    return '<span class="badge bg-soft-danger text-danger">
                                <span class="legend-indicator bg-danger"></span>' . trans('Holded') . '
                              </span>';
                }else{
                    return '<span class="badge bg-soft-dark text-dark">
                                <span class="legend-indicator bg-dark"></span>' . trans('Unknown') . '
                              </span>';
                }
            })
            ->addColumn('action', function ($item) {
                $editUrl = route('admin.property.edit', $item->property_id);
                $deleteUrl = route('admin.report.delete', $item->id);
                return '<div class="btn-group" role="group">
                      <a href="' . $editUrl . '" class="btn btn-white btn-sm edit_user_btn">
                        <i class="bi-pencil-square me-1"></i> ' . trans("property") . '
                      </a>
                    <div class="btn-group">
                      <button type="button" class="btn btn-white btn-icon btn-sm dropdown-toggle dropdown-toggle-empty" id="userEditDropdown" data-bs-toggle="dropdown" aria-expanded="false"></button>
                      <div class="dropdown-menu dropdown-menu-end mt-1" aria-labelledby="userEditDropdown">
                        <a class="dropdown-item deleteSingleBtn text-danger" href="javascript:void(0)"
                           data-route="' . $deleteUrl . '"
                           data-bs-toggle="modal" data-bs-target="#deleteModal">
                            <i class="bi bi-trash dropdown-item-icon text-danger"></i>
                            ' . trans("Delete") . '
                        </a>
                      </div>
                    </div>
                  </div>';
            })->rawColumns(['action', 'checkbox', 'name', 'property', 'report', 'status', 'reported_at'])
            ->make(true);
    }

    public function reportDelete($id)
    {
        try {
            $report = Report::where('id', $id)->firstOr(function (){
                throw new \Exception('Report not found');
            });

            $report->delete();

            return back()->with('success', 'Deleted Successfully');
        }catch (\Exception $exception){
            return  back()->with('error', $exception->getMessage());
        }
    }

    public function reportDeleteMultiple(Request $request)
    {
        if ($request->strIds == null) {
            session()->flash('error', 'You do not select User.');
            return response()->json(['error' => 1]);
        } else {
            Report::whereIn('id', $request->strIds)->get()->map(function ($report) {
                $report->forceDelete();
            });
            session()->flash('success', 'Reports has been deleted successfully');
            return response()->json(['success' => 1]);
        }
    }

    public function feedback(Request $request)
    {
        $currentMonth = now()->month;
        $currentYear = now()->year;

        $data['feedbackStats'] = Feedback::selectRaw("
        COUNT(*) as total,
        SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END) as active,
        SUM(CASE WHEN status = 0 THEN 1 ELSE 0 END) as inactive,
        SUM(CASE WHEN MONTH(created_at) = ? AND YEAR(created_at) = ? THEN 1 ELSE 0 END) as this_month,
        SUM(CASE WHEN YEAR(created_at) = ? THEN 1 ELSE 0 END) as this_year
    ", [$currentMonth, $currentYear, $currentYear])->first();

        $total = $data['feedbackStats']->total ?? 1;

        $data['percentages'] = [
            'active' =>$total ? round(($data['feedbackStats']->active / $total) * 100, 2):0,
            'inactive' => $total ? round(($data['feedbackStats']->inactive / $total) * 100, 2):0,
            'this_month' => $total ? round(($data['feedbackStats']->this_month / $total) * 100, 2):0,
            'this_year' => $total ? round(($data['feedbackStats']->this_year / $total) * 100, 2):0,
        ];

        return view('admin.reportAndFeedback.feedback', $data);
    }

    public function feedbackSearch(Request $request)
    {
        $search = $request->search['value'];
        $filterSearch = $request->filterSearch;
        $filterDate = explode('-', $request->filterDate);
        $startDate = $filterDate[0];
        $endDate = isset($filterDate[1]) ? trim($filterDate[1]) : null;

        $category = Feedback::query()
            ->with(['user'])
            ->orderBy('id', 'DESC')
            ->when(!empty($search), function ($query) use ($search) {
                return $query->whereHas('user', function ($query) use ($search) {
                    return $query->where('firstname', 'like', '%' . $search . '%')
                        ->orWhere('lastname', 'like', '%' . $search . '%');
                })
                    ->orWhere('details', 'like', '%' . $search . '%');
            })
            ->when(isset($filterSearch) && !empty($filterSearch), function ($query) use ($filterSearch) {
                return $query->whereHas('user', function ($query) use ($filterSearch) {
                    return $query->where('firstname', 'like', '%' . $filterSearch . '%')
                        ->orWhere('lastname', 'like', '%' . $filterSearch . '%');
                })
                    ->orWhere('details', 'like', '%' . $filterSearch . '%');
            })
            ->when(!empty($request->filterDate) && $endDate != null, function ($query) use ($startDate, $endDate) {
                $startDate = Carbon::createFromFormat('d/m/Y', trim($startDate));
                $endDate = Carbon::createFromFormat('d/m/Y', trim($endDate));
                $query->whereBetween('created_at', [$startDate, $endDate]);
            });

        return DataTables::of($category)
            ->addColumn('checkbox', function ($item) {
                return '<input type="checkbox" id="chk-' . $item->id . '"
                    class="form-check-input row-tic tic-check"
                    name="check" value="' . $item->id . '"
                    data-id="' . $item->id . '">';
            })

            ->addColumn('user', function ($item) {
                $user = optional($item->user);
                $image = $user->image ?? null;
                $firstLetter = substr($user->firstname ?? '', 0, 1);
                $fullName = trim($user->firstname . ' ' . $user->lastname);
                $titleHtml = '<span class="fs-6 text-body ps-1">' . e($fullName) . '</span>';

                if (!$image) {
                    return '<div class="d-flex align-items-center">
                        <div class="avatar avatar-sm avatar-soft-primary avatar-circle">
                            <span class="avatar-initials">' . e($firstLetter) . '</span>
                        </div>' . $titleHtml . '
                    </div>';
                }

                $url = getFile($user->image_driver, $user->image);
                return '<div class="d-flex align-items-center">
                    <div class="avatar avatar-sm avatar-circle">
                        <img class="avatar-img" src="' . e($url) . '" alt="User Image" style="width:32px; height:32px; object-fit:cover;">
                    </div>' . $titleHtml . '
                </div>';
            })

            ->addColumn('details', function ($item) {
                $plainText = strip_tags($item->details);
                $shortText = strlen($plainText) > 50 ? substr($plainText, 0, 50) . '...' : $plainText;

                return '
                    <span class="short-details">' . e($shortText) . '</span>
                    <a href="javascript:void(0);" class="see-more-btn" data-fullreport="' . e($item->details) . '">See more</a>
                ';
            })
            ->addColumn('status', function ($item) {
                if ($item->status == 1) {
                    return '<span class="badge bg-soft-success text-success">
                                <span class="legend-indicator bg-success"></span>' . trans('Active') . '
                              </span>';
                } elseif ($item->status == 0) {
                    return '<span class="badge bg-soft-danger text-danger">
                                <span class="legend-indicator bg-danger"></span>' . trans('Holded') . '
                              </span>';
                }else{
                    return '<span class="badge bg-soft-dark text-dark">
                                <span class="legend-indicator bg-dark"></span>' . trans('Unknown') . '
                              </span>';
                }
            })

            ->addColumn('action', function ($item) {
                $deleteUrl = route('admin.feedback.delete', $item->id);

                return '<div class="btn-group" role="group">
                    <a href="javascript:void(0)"
                       class="btn btn-white btn-sm deleteSingleBtn text-danger"
                       data-route="' . e($deleteUrl) . '"
                       data-bs-toggle="modal"
                       data-bs-target="#deleteModal">
                        <i class="bi bi-trash dropdown-item-icon text-danger"></i> ' . trans("Delete") . '
                    </a>
                </div>';
            })

            ->rawColumns(['action', 'checkbox', 'user', 'details','status'])
            ->make(true);
    }

    public function feedbackDelete($id)
    {
        try {
            $feedback = Feedback::where('id', $id)->firstOr(function (){
                throw new \Exception('Feedback not found');
            });

            $feedback->delete();

            return back()->with('success', 'Deleted Successfully');
        }catch (\Exception $exception){
            return  back()->with('error', $exception->getMessage());
        }
    }

    public function feedbackDeleteMultiple(Request $request)
    {
        if ($request->strIds == null) {
            session()->flash('error', 'You do not select User.');
            return response()->json(['error' => 1]);
        } else {
            Feedback::whereIn('id', $request->strIds)->get()->map(function ($feedback) {
                $feedback->forceDelete();
            });
            session()->flash('success', 'Feedbacks has been deleted successfully');
            return response()->json(['success' => 1]);
        }
    }
}
