<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Affiliate;
use App\Models\AffiliateEarning;
use App\Models\Booking;
use App\Models\Deposit;
use App\Models\Payout;
use App\Models\Property;
use App\Models\SupportTicket;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserKyc;
use App\Models\UserLogin;
use App\Traits\Notify;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Traits\Upload;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class DashboardController extends Controller
{
    use Upload, Notify;

    public function index()
    {
        $data['firebaseNotify'] = config('firebase');
        $data['latestUser'] = User::latest()->limit(5)->get();
        $statistics['schedule'] = $this->dayList();
        $data['hosts'] = User::select(['id','role','firstname','lastname','username'])->where('role', 1)->get();
        $data['total_user'] = User::select(['id','status'])->where('status', 1)->count();
        $data['pending_tickets'] = SupportTicket::select(['id','status'])->where('status', 0)->count();
        $data['pending_kyc'] = UserKyc::select(['id','status'])->where('status', 0)->count();
        $data['this_month_transactions'] = Transaction::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();

        $data['total_host'] = User::select(['id','status','role'])->where('role', 1)->where('status', 1)->count();
        $data['total_booking'] = Booking::select(['id','status'])->where('status', '!=', 0)->count();
        $data['total_booking_amount'] = Booking::select(['id','status','total_amount'])->where('status', '!=', 0)->sum('total_amount');
        $data['total_host_earning'] = Booking::select(['id','status','host_received'])->where('status', '!=', 0)->sum('host_received');
        $data['total_platform_earning'] = Booking::select(['id','status','site_charge'])->where('status', '!=', 0)->sum('site_charge');
        $data['total_property'] = Property::select(['id','status'])->where('status', '!=', 0)->count();
        $data['total_affiliate'] = Affiliate::select(['id','status'])->where('status', 1)->count();
        $data['total_affiliate_earning'] = AffiliateEarning::select(['id','status','amount'])->sum('amount');


        return view('admin.dashboard-alternative', $data, compact("statistics"));
    }
    public function monthlyDepositWithdraw(Request $request)
    {
        $keyDataset = $request->keyDataset;

        $dailyDeposit = $this->dayList();

        Deposit::when($keyDataset == '0', function ($query) {
            $query->whereMonth('created_at', Carbon::now()->month);
        })
            ->when($keyDataset == '1', function ($query) {
                $lastMonth = Carbon::now()->subMonth();
                $query->whereMonth('created_at', $lastMonth->month);
            })
            ->select(
                DB::raw('SUM(payable_amount_in_base_currency) as totalDeposit'),
                DB::raw('DATE_FORMAT(created_at,"Day %d") as date')
            )
            ->groupBy(DB::raw("DATE(created_at)"))
            ->get()->map(function ($item) use ($dailyDeposit) {
                $dailyDeposit->put($item['date'], $item['totalDeposit']);
            });

        return response()->json([
            "totalDeposit" => currencyPosition($dailyDeposit->sum()),
            "dailyDeposit" => $dailyDeposit,
        ]);
    }

    public function saveToken(Request $request)
    {
        $admin = Auth::guard('admin')->user()
            ->fireBaseToken()
            ->create([
                'token' => $request->token,
            ]);
        return response()->json([
            'msg' => 'token saved successfully.',
        ]);
    }


    public function dayList()
    {
        $totalDays = Carbon::now()->endOfMonth()->format('d');
        $daysByMonth = [];
        for ($i = 1; $i <= $totalDays; $i++) {
            array_push($daysByMonth, ['Day ' . sprintf("%02d", $i) => 0]);
        }

        return collect($daysByMonth)->collapse();
    }

    protected function followupGrap($todaysRecords, $lastDayRecords = 0)
    {

        if (0 < $lastDayRecords) {
            $percentageIncrease = (($todaysRecords - $lastDayRecords) / $lastDayRecords) * 100;
        } else {
            $percentageIncrease = 0;
        }
        if ($percentageIncrease > 0) {
            $class = "bg-soft-success text-success";
        } elseif ($percentageIncrease < 0) {
            $class = "bg-soft-danger text-danger";
        } else {
            $class =  "bg-soft-secondary text-body";
        }

        return [
            'class' => $class,
            'percentage' => round($percentageIncrease, 2)
        ];
    }


    public function chartBrowserHistory(Request $request)
    {
        $startDate = $request->startDate;
        $endDate = $request->endDate;

        $userLoginsData = DB::table('user_logins')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->select('browser', 'os', 'get_device')
            ->get();

        $userLoginsBrowserData = $userLoginsData->groupBy('browser')->map->count();
        $data['browserKeys'] = $userLoginsBrowserData->keys();
        $data['browserValue'] = $userLoginsBrowserData->values();

        return response()->json(['browserPerformance' => $data]);
    }

    public function chartOsHistory(Request $request)
    {
        $startDate = $request->startDate;
        $endDate = $request->endDate;

        $userLoginsData = DB::table('user_logins')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->select('browser', 'os', 'get_device')
            ->get();

        $userLoginsOSData = $userLoginsData->groupBy('os')->map->count();
        $data['osKeys'] = $userLoginsOSData->keys();
        $data['osValue'] = $userLoginsOSData->values();

        return response()->json(['osPerformance' => $data]);
    }

    public function chartDeviceHistory(Request $request)
    {
        $startDate = $request->startDate;
        $endDate = $request->endDate;

        $userLoginsData = DB::table('user_logins')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->select('browser', 'os', 'get_device')
            ->get();

        $userLoginsDeviceData = $userLoginsData->groupBy('get_device')->map->count();
        $data['deviceKeys'] = $userLoginsDeviceData->keys();
        $data['deviceValue'] = $userLoginsDeviceData->values();

        return response()->json(['deviceHistory' => $data]);
    }
    public function getUserLocations()
    {
        $countries = config('country') ?? [];

        $locations = UserLogin::selectRaw('country, country_code, latitude, longitude, COUNT(user_id) as total_users')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->groupBy('country', 'country_code', 'latitude', 'longitude')
            ->orderByDesc('total_users')
            ->get()
            ->map(function ($location) use ($countries) {
                $countryData = collect($countries)->firstWhere('code', $location->country_code);
                $flag = $countryData['flag'] ?? null;

                return [
                    "coords" => [floatval($location->latitude), floatval($location->longitude)],
                    "name" => $location->country,
                    "active" => $location->total_users,
                    "new" => rand(1, $location->total_users),
                    "flag" => asset($flag),
                    "code" => $location->country_code
                ];
            });

        return response()->json($locations);
    }

    public function getAffiliateActivityData(Request $request)
    {
        $topAffiliates = Affiliate::query()
            ->withSum(['earnings as pending_earnings' => function ($query) {
                $query->where('status', 0);
            }], 'amount')
            ->withSum(['earnings as completed_earnings' => function ($query) {
                $query->where('status', 1);
            }], 'amount')
            ->withSum('earnings as total_earnings', 'amount')
            ->where('status', 1)
            ->orderByDesc('total_earnings')
            ->take(10)
            ->get()
            ->map(function ($affiliate) {
                return [
                    'id' => $affiliate->id,
                    'name' => trim($affiliate->firstname . ' ' . $affiliate->lastname),
                    'country' => $affiliate->country ?? 'Unknown',
                    'phone' => ($affiliate->phone_code.$affiliate->phone) ?? 'Unknown',
                    'balance' => $affiliate->balance ?? 0,
                    'total_click' => $affiliate->total_click ?? 0,
                    'pending_earnings' => $affiliate->pending_earnings ?? 0,
                    'completed_earnings' => $affiliate->completed_earnings ?? 0,
                    'total_earnings' => $affiliate->total_earnings ?? 0,
                ];
            });

        return response()->json([
            'labels' => $topAffiliates->pluck('name'),
            'topAffiliates' => $topAffiliates,
            'datasets' => [
                [
                    'label' => 'Total Clicks',
                    'data' => $topAffiliates->pluck('total_click'),
                    'borderColor' => '#36A2EB',
                    'backgroundColor' => 'rgba(54,162,235,0.2)',
                    'tension' => 0.4,
                    'fill' => true,
                ]
            ]
        ]);
    }

    public function getDepositChart(Request $request)
    {
        $start = $request->start;
        $end = $request->end ?? $start;

        $dailyDeposit = collect();

        Deposit::query()
            ->where('status',1)
            ->whereBetween('created_at', [$start, $end])
            ->select(
                DB::raw('SUM(payable_amount_in_base_currency) as totalDeposit'),
                DB::raw('DATE_FORMAT(created_at,"%d %b") as date')
            )
            ->groupBy(DB::raw("DATE(created_at)"))
            ->get()
            ->each(function ($item) use ($dailyDeposit) {
                $dailyDeposit->put($item->date, $item->totalDeposit);
            });

        return response()->json([
            "dates" => $dailyDeposit->keys(),
            "totalDeposit" => $dailyDeposit->values()
        ]);
    }

    public function getPayoutChart(Request $request)
    {
        $start = $request->start;
        $end = $request->end ?? $start;

        $dailyPayout = collect();

        Payout::query()
            ->whereBetween('created_at', [$start, $end])
            ->select(
                DB::raw('SUM(amount_in_base_currency) as totalPayout'),
                DB::raw('DATE_FORMAT(created_at,"%d %b") as date')
            )
            ->groupBy(DB::raw("DATE(created_at)"))
            ->get()
            ->each(function ($item) use ($dailyPayout) {
                $dailyPayout->put($item->date, $item->totalPayout);
            });

        return response()->json([
            "dates" => $dailyPayout->keys(),
            "totalPayout" => $dailyPayout->values()
        ]);
    }
}
