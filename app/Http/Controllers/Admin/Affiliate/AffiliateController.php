<?php

namespace App\Http\Controllers\Admin\Affiliate;

use App\Http\Controllers\Controller;
use App\Models\Affiliate;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class AffiliateController extends Controller
{
    public function index(Request $request)
    {
        $now = Carbon::now();
        $currentMonth = $now->month;
        $currentYear = $now->year;

        $stats = DB::table('affiliates')
            ->selectRaw('
            COUNT(*) as total,
            SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END) as active,
            SUM(CASE WHEN status = 0 THEN 1 ELSE 0 END) as inactive,
            SUM(CASE WHEN MONTH(created_at) = ? AND YEAR(created_at) = ? THEN 1 ELSE 0 END) as this_month,
            SUM(CASE WHEN YEAR(created_at) = ? THEN 1 ELSE 0 END) as this_year
        ', [$currentMonth, $currentYear, $currentYear])
            ->first();
        $total = $stats->total > 0 ? $stats->total : 1;

        $data['total'] = $stats->total;
        $data['active'] = $stats->active;
        $data['inactive'] = $stats->inactive;
        $data['this_month'] = $stats->this_month;
        $data['this_year'] = $stats->this_year;

        $data['active_percentage'] = round(($stats->active / $total) * 100, 2);
        $data['inactive_percentage'] = round(($stats->inactive / $total) * 100, 2);
        $data['this_month_percentage'] = round(($stats->this_month / $total) * 100, 2);
        $data['this_year_percentage'] = round(($stats->this_year / $total) * 100, 2);

        $data['status'] = $request->status ?? 'all';

        return view('admin.affiliate_management.list', $data);
    }

    public function search(Request $request)
    {
        $search = $request->search['value'];
        $filterSearch = $request->filterSearch;
        $filterStatus = $request->filterStatus;
        $filterDate = explode('-', $request->filterDate);
        $startDate = $filterDate[0];
        $endDate = isset($filterDate[1]) ? trim($filterDate[1]) : null;
        $status = $request->status;

        $affiliates= Affiliate::query()
            ->with(['kycs'])
            ->orderBy('id', 'DESC')
            ->when(!empty($search), function ($query) use ($search) {
                return $query->where('firstname', 'LIKE', "%{$search}%")
                    ->orWhere('lastname', 'LIKE', "%{$search}%")
                    ->orWhere('username', 'LIKE', "%{$search}%");
            })
            ->when(isset($filterSearch) && !empty($filterSearch), function ($query) use ($filterSearch) {
                return $query->where('firstname', 'LIKE', "%{$filterSearch}%")
                    ->orWhere('lastname', 'LIKE', "%{$filterSearch}%")
                    ->orWhere('username', 'LIKE', "%{$filterSearch}%");
            })
            ->when(isset($filterStatus), function ($query) use ($filterStatus) {
                if ($filterStatus == 'all') {
                    return $query->where('status', '!=', null);
                }elseif ($filterStatus == '1'){
                    return $query->where('status', 1);
                }elseif ($filterStatus == '0'){
                    return $query->where('status', 0);
                }elseif ($filterStatus == '3'){
                    return $query->where('identity_verify', '!=', 2);
                }
            })
            ->when(isset($status), function ($query) use ($status) {
                if ($status == 'all') {
                    return $query->where('status', '!=', null);
                }elseif ($status == 'active'){
                    return $query->where('status', 1);
                }elseif ($status == 'inactive'){
                    return $query->where('status', 0);
                }elseif ($status == 'pending'){
                    return $query->where('identity_verify', '!=', 2);
                }
            })
            ->when(!empty($request->filterDate) && $endDate != null, function ($query) use ($startDate, $endDate) {
                $startDate = Carbon::createFromFormat('d/m/Y', trim($startDate));
                $endDate = Carbon::createFromFormat('d/m/Y', trim($endDate));
                $query->whereBetween('created_at', [$startDate, $endDate]);
            })
            ->get();

        return DataTables::of($affiliates)

            ->addColumn('checkbox', function ($item) {
                return ' <input type="checkbox" id="chk-' . $item->id . '"
                                       class="form-check-input row-tic tic-check" name="check" value="' . $item->id . '"
                                       data-id="' . $item->id . '">';
            })
            ->addColumn('affiliate', function ($item) {
                $url = route('admin.affiliate.profile.view', $item->id);
                return '<a class="d-flex align-items-center me-2" href="' . $url . '">
                                <div class="flex-shrink-0">
                                  ' . $item->profilePicture() . '
                                </div>
                                <div class="flex-grow-1 ms-3">
                                  <h5 class="text-hover-primary mb-0">' . $item->firstname . ' ' . $item->lastname . '</h5>
                                  <span class="fs-6 text-body">@' . $item->username . '</span>
                                </div>
                              </a>';
            })
            ->addColumn('balance', function ($item) {
                return '<span class="badge bg-soft-primary text-dark">' . currencyPosition($item->balance) . '</span>';
            })
            ->addColumn('status', function ($item) {
                if ($item->status == 1) {
                    return '<span class="badge bg-soft-primary text-primary">
                                <span class="legend-indicator bg-primary"></span>' . trans('Active') . '
                              </span>';
                } elseif ($item->status == 0) {
                    return '<span class="badge bg-soft-secondary text-secondary">
                                <span class="legend-indicator bg-secondary"></span>' . trans('Inactive') . '
                              </span>';
                } else {
                    return '<span class="badge bg-soft-info text-info">
                                <span class="legend-indicator bg-info"></span>' . trans('Unknown') . '
                              </span>';
                }
            })
            ->addColumn('kyc', function ($item) {
                if ($item->identity_verify == 2) {
                    return '<span class="badge bg-soft-success text-success">
                                <span class="legend-indicator bg-success"></span>' . trans('Verified') . '
                              </span>';
                } elseif ($item->identity_verify == 1) {
                    return '<span class="badge bg-soft-warning text-warning">
                                <span class="legend-indicator bg-warning"></span>' . trans('Pending') . '
                              </span>';
                }elseif ($item->identity_verify == 0) {
                    return '<span class="badge bg-soft-danger text-danger">
                                <span class="legend-indicator bg-danger"></span>' . trans('Not Applied') . '
                              </span>';
                }elseif ($item->identity_verify == 3) {
                    return '<span class="badge bg-soft-danger text-danger">
                                <span class="legend-indicator bg-danger"></span>' . trans('Rejected') . '
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
                $editUrl = route('admin.affiliate.profile.edit', $item->id);
                $deleteUrl = route('admin.affiliate.profile.delete', $item->id);
                $statusUrl = route('admin.affiliate.profile.status', $item->id);
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
                        <a class="dropdown-item deleteSingleBtn text-danger" href="javascript:void(0)"
                           data-route="'. $deleteUrl .'"
                           data-bs-toggle="modal" data-bs-target="#deleteModal">
                            <i class="bi bi-trash dropdown-item-icon text-danger"></i>
                            '. trans("Delete") .'
                        </a>
                      </div>
                    </div>
                  </div>';
            })->rawColumns(['action', 'checkbox', 'affiliate','balance', 'status', 'created_at','kyc'])
            ->make(true);
    }

    public function searchCountData()
    {
        $userData = Affiliate::selectRaw(
            'COUNT(*) as totalAffiliate,
             SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END) as totalActiveAffiliate,
             SUM(CASE WHEN status = 0 THEN 1 ELSE 0 END) as totalBannedAffiliate,
             SUM(CASE WHEN identity_verify IN (0, 1) THEN 1 ELSE 0 END) as pendingKyc'
        )->first();

        $data['total_affiliate'] = $userData->totalAffiliate ?? 0;
        $data['active_affiliate'] = $userData->totalActiveAffiliate ?? 0;
        $data['inactive_affiliate'] = $userData->totalBannedAffiliate ?? 0;
        $data['pending_kyc'] = $userData->pendingKyc ?? 0;

        return $data;
    }

    public function loginAs($id){
        Auth::guard('affiliate')->loginUsingId($id);
        return redirect()->route('affiliate.dashboard');
    }
}
