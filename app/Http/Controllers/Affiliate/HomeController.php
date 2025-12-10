<?php

namespace App\Http\Controllers\Affiliate;

use App\Http\Controllers\Controller;
use App\Models\Affiliate;
use App\Models\AffiliateClick;
use App\Models\AffiliateEarning;
use App\Models\AffiliateStatistics;
use App\Models\Language;
use App\Models\Payout;
use App\Models\Property;
use App\Models\Transaction;
use App\Models\User;
use App\Services\BasicService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Jenssegers\Agent\Agent;
use Torann\GeoIP\Facades\GeoIP;
use Illuminate\Support\Facades\Storage;
use Barryvdh\Snappy\Facades\SnappyPdf as PDF;

class HomeController extends Controller
{
    public function dashboard()
    {
        $affiliateAuth = auth('affiliate');
        $affiliateId = $affiliateAuth->id();

        $data['balance'] = $affiliateAuth->user()->balance ?? 0.00;
        $data['pending_balance'] = AffiliateEarning::where('affiliate_id', $affiliateId)->where('status', 0)->sum('amount') ?? 0.00;
        $data['total_click'] = auth()->user()->total_click ?? 0;
        $baseCliick = AffiliateClick::where('affiliate_id', $affiliateId);

        $today = Carbon::today();
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();
        $startOfMonth = Carbon::now()->startOfMonth();
        $startOfLastMonth = Carbon::now()->subMonth()->startOfMonth();
        $endOfLastMonth = Carbon::now()->subMonth()->endOfMonth();

        $transactions = Transaction::where('affiliate_id', $affiliateId)
            ->where('transactional_type', AffiliateStatistics::class)
            ->where('created_at', '>=', $startOfLastMonth)
            ->get();

        $clicks = $baseCliick->where('created_at', '>=', $startOfLastMonth)
            ->get();

        $todayEarning = $transactions->whereBetween('created_at', [$today, $today->endOfDay()])->sum('amount');
        $thisWeekEarning = $transactions->whereBetween('created_at', [$startOfWeek, $endOfWeek])->sum('amount');
        $thisMonthEarning = $transactions->whereBetween('created_at', [$startOfMonth, now()])->sum('amount');
        $lastMonthEarning = $transactions->whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])->sum('amount');

        $thisMonthTransactions = $transactions->whereBetween('created_at', [$startOfMonth, now()])->count();
        $thisMonthClicks = $clicks->whereBetween('created_at', [$startOfMonth, now()])->count();

        $lastMonthTransactions = $transactions->whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])->count();
        $lastMonthClicks = $clicks->whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])->count();

        $thisMonthConversion = $thisMonthClicks > 0
            ? round(($thisMonthTransactions / $thisMonthClicks) * 100, 2)
            : 0;

        $lastMonthConversion = $lastMonthClicks > 0
            ? round(($lastMonthTransactions / $lastMonthClicks) * 100, 2)
            : 0;

        $conversionGrowth = $lastMonthConversion > 0
            ? (($thisMonthConversion - $lastMonthConversion) / $lastMonthConversion) * 100
            : ($thisMonthConversion > 0 ? 100 : 0);

        $earningGrowth = $lastMonthEarning > 0
            ? (($thisMonthEarning - $lastMonthEarning) / $lastMonthEarning) * 100
            : ($thisMonthEarning > 0 ? 100 : 0);

        $totalClick = auth()->user()->total_click ?? 0;
        $thisMonthClickPercentage = $totalClick > 0
            ? round(($thisMonthClicks / $totalClick) * 100, 2)
            : 0;

        $data['today_earning'] = round($todayEarning, 2);
        $data['this_week_earning'] = round($thisWeekEarning, 2);
        $data['this_month_earning'] = round($thisMonthEarning, 2);
        $data['last_month_earning'] = round($lastMonthEarning, 2);
        $data['this_month_transaction_count'] = $thisMonthTransactions;
        $data['thisMonthConversionPercentage'] = $thisMonthConversion;
        $data['lastMonthConversionPercentage'] = $lastMonthConversion;
        $data['conversionGrowthPercentage'] = round($conversionGrowth, 2);
        $data['earning_growth_percentage'] = round($earningGrowth, 2);
        $data['this_month_click_percentage'] = round($thisMonthClickPercentage, 2);

        $data['this_month_clicks'] = $thisMonthClicks;


        $data['topProperties'] = AffiliateStatistics::query()->with('property.photos')
            ->leftJoin('transactions', function ($join) {
                $join->on('transactions.transactional_id', '=', 'affiliate_statistics.id')
                    ->where('transactions.transactional_type', '=', AffiliateStatistics::class);
            })
            ->where('affiliate_statistics.affiliate_id', $affiliateId)
            ->select(
                'affiliate_statistics.property_id',
                DB::raw('SUM(affiliate_statistics.total_click) as total_clicks'),
                DB::raw('COUNT(transactions.id) as transaction_count'),
                DB::raw('SUM(transactions.amount) as total_amount')
            )
            ->groupBy('affiliate_statistics.property_id')
            ->orderByDesc('total_clicks')
            ->get();

        $data['recentAffiliateClicks'] = $baseCliick->with('property')->latest()->get();

        return view(template() . 'affiliate.dashboard', $data);
    }
    public function itemList(Request $request)
    {
        $userId = auth('affiliate')->id();

        $items = Property::with(['photos'])
            ->where('status', 1)
            ->where('is_affiliatable', 1)
            ->withCount(['affiliateClick as total_click' => function ($query) use ($userId) {
                $query->where('affiliate_id', $userId);
            }])
            ->paginate(basicControl()->paginate);

        $items->getCollection()->transform(function ($item) {
            $item->url = route('service.details', $item->slug);
            $item->thumb = getFile($item->photos->images['thumb']['driver'], $item->photos->images['thumb']['path']);
            $item->vanity_url = vanitiyLink(auth()->user(), $item->affiliate_slug);

            return $item;
        });

        return view(template() . 'affiliate.list', ['items' => $items]);
    }
    public function affiliateClick($username = null, $vanityLink = null)
    {
        try {
            $basic = basicControl();

            if (!$username || !$vanityLink || !$basic->affiliate_status) {
                throw new \Exception('Invalid affiliate link.');
            }

            $affiliate = Affiliate::where('username', $username)
                ->where('status', 1)
                ->where('is_affiliatable', 1)
                ->first();

            $listing = Property::where('affiliate_slug', $vanityLink)
                ->where('is_affiliatable', 1)
                ->where('status', 1)
                ->first();

            if (!$affiliate || !$listing) {
                throw new \Exception('Affiliate or listing not found.');
            }

            AffiliateClick::create([
                'affiliate_id' => $affiliate->id,
                'property_id' => $listing->id,
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'referer' => request()->utm_source,
                'session_id' => session()->getId(),
            ]);

            $affiliate->increment('total_click');
            $listing->increment('total_click');

            AffiliateStatistics::updateOrCreate(
                ['affiliate_id' => $affiliate->id, 'property_id' => $listing->id],
                ['total_click' => DB::raw('total_click + 1')]
            );

            session()->put('affiliate', [
                'affiliate_id' => $affiliate->id,
                'property_id' => $listing->id,
            ]);
            cookie()->queue('affiliate_id', $affiliate->id, 60 * 24 * 30);

            return redirect()->route('service.details', $listing->slug);

        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
    public function affiliateChart(Request $request)
    {
        $range = $request->input('range', 30);
        $labels = [];
        $earnings = [];

        if ($range === 'year') {
            $earningsData = Transaction::select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('SUM(amount) as total')
            )
                ->where('affiliate_id', auth('affiliate')->id())
                ->where('transactional_type', AffiliateEarning::class)
                ->whereYear('created_at', Carbon::now()->year)
                ->groupBy(DB::raw('MONTH(created_at)'))
                ->pluck('total', 'month');

            for ($i = 1; $i <= 12; $i++) {
                $labels[] = Carbon::create()->month($i)->format('M');
                $earnings[] = $earningsData[$i] ?? 0;
            }
        } else {
            $startDate = Carbon::now()->subDays($range - 1)->startOfDay();
            $endDate = Carbon::now()->endOfDay();
            $days = is_numeric($range) ? intval($range) : 30;

            $earningsData = Transaction::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(amount) as total')
            )
                ->where('affiliate_id', auth('affiliate')->id())
                ->where('transactional_type', AffiliateEarning::class)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->groupBy(DB::raw('DATE(created_at)'))
                ->pluck('total', 'date');

            for ($i = 0; $i < $days; $i++) {
                $date = Carbon::now()->subDays($days - 1 - $i);
                $dateKey = $date->format('Y-m-d');

                if ($range > 30) {
                    $labels[] = $date->format('M j');
                } else {
                    $labels[] = $date->format('M j, Y');
                }

                $earnings[] = $earningsData[$dateKey] ?? 0;
            }
        }

        return response()->json([
            'labels' => $labels,
            'earning' => $earnings,
        ]);
    }
    public function profile()
    {
        $data = [];
        if (auth('affiliate')->user()->language_id) {
            $data['userLanguage'] = Language::where('id', auth()->user()->language_id)->first();
        }
        return view(template() . 'affiliate.profile.profile', $data);
    }
    public function transaction(Request $request)
    {
        $data['transactions'] = Transaction::where('affiliate_id', auth('affiliate')->id())
            ->orderBy('id', 'desc')
            ->when($request->transaction_id, fn($q, $trx) => $q->where('trx_id', $trx))
            ->when($request->datefilter, function ($q, $range) {
                $dates = explode(' - ', $range);
                if (count($dates) === 2) {
                    $start = \Carbon\Carbon::createFromFormat('d/m/Y', $dates[0])->startOfDay();
                    $end = \Carbon\Carbon::createFromFormat('d/m/Y', $dates[1])->endOfDay();
                    $q->whereBetween('created_at', [$start, $end]);
                }
            })
            ->paginate(basicControl()->paginate);

        return view(template() . 'affiliate.transactions', $data);
    }
    public function information(Request $request)
    {
        return view(template() . 'affiliate.profile.account_informations');
    }
    public function payouts(Request $request)
    {
        $fromDate = null;
        $toDate = null;

        if ($request->filled('datefilter')) {
            [$from, $to] = explode(' - ', $request->datefilter);
            try {
                $fromDate = \Carbon\Carbon::createFromFormat('d/m/Y', trim($from))->startOfDay();
                $toDate = \Carbon\Carbon::createFromFormat('d/m/Y', trim($to))->endOfDay();
            } catch (\Exception $e) {
                return back()->with('error', 'Invalid date range');
            }
        }

        $payouts = Payout::with('affiliates')
            ->where('affiliate_id', auth('affiliate')->id())
            ->orderByDesc('id')
            ->where('status', '!=', 0)
            ->when($request->filled('transaction_id'), function ($query) use ($request) {
                $query->where('trx_id', $request->transaction_id);
            })
            ->when($fromDate && $toDate, function ($query) use ($fromDate, $toDate) {
                $query->whereBetween('created_at', [$fromDate, $toDate]);
            })
            ->paginate(basicControl()->paginate);

        return view(template() . 'affiliate.payout.index', compact('payouts'));
    }
    public function analytics()
    {
        $affiliateId = auth('affiliate')->id();

        $startOfThisWeek = Carbon::now()->startOfWeek();
        $endOfThisWeek = Carbon::now()->endOfWeek();
        $startOfLastWeek = Carbon::now()->subWeek()->startOfWeek();
        $endOfLastWeek = Carbon::now()->subWeek()->endOfWeek();

        $totals = AffiliateClick::where('affiliate_id', $affiliateId)
            ->selectRaw("
            SUM(CASE WHEN created_at BETWEEN ? AND ? THEN 1 ELSE 0 END) AS this_week_visits,
            SUM(CASE WHEN created_at BETWEEN ? AND ? THEN 1 ELSE 0 END) AS last_week_visits
        ", [$startOfThisWeek, $endOfThisWeek, $startOfLastWeek, $endOfLastWeek])
            ->first();

        $totalVisitsThisWeek = $totals->this_week_visits ?? 0;
        $totalVisitsLastWeek = $totals->last_week_visits ?? 0;

        if ($totalVisitsLastWeek == 0) {
            $percentageChange = $totalVisitsThisWeek > 0 ? 100 : 0;
        } else {
            $percentageChange = (($totalVisitsThisWeek - $totalVisitsLastWeek) / $totalVisitsLastWeek) * 100;
        }
        $percentageChange = round($percentageChange, 2);

        $bounceRateThisWeek = $this->getBounceRate($affiliateId, $startOfThisWeek, $endOfThisWeek);
        $bounceRateLastWeek = $this->getBounceRate($affiliateId, $startOfLastWeek, $endOfLastWeek);

        if ($bounceRateLastWeek == 0) {
            $percentageBounceChange = $bounceRateThisWeek > 0 ? 100 : 0;
        } else {
            $percentageBounceChange = (($bounceRateThisWeek - $bounceRateLastWeek) / $bounceRateLastWeek) * 100;
        }
        $percentageBounceChange = round($percentageBounceChange, 2);

        $avgSessionThisWeek = $this->getAverageSessionTimeByPeriod($affiliateId, $startOfThisWeek, $endOfThisWeek);
        $avgSessionLastWeek = $this->getAverageSessionTimeByPeriod($affiliateId, $startOfLastWeek, $endOfLastWeek);

        if ($avgSessionLastWeek == 0) {
            $percentageSessionChange = $avgSessionThisWeek > 0 ? 100 : 0;
        } else {
            $percentageSessionChange = (($avgSessionThisWeek - $avgSessionLastWeek) / $avgSessionLastWeek) * 100;
        }

        $percentageSessionChange = round($percentageSessionChange, 2);

        $formatTime = fn($seconds) => ($seconds < 0 ? '-' : '') . sprintf("%dm %02ds", floor(abs($seconds) / 60), round(abs($seconds) % 60));

        $uniqueStats = $this->getUniqueVisitorsStats($affiliateId);

        $clicks = AffiliateClick::with([
            'property.photos',
            'property.pricing'
        ])
            ->where('affiliate_id', auth('affiliate')->id())
            ->select('property_id', 'referer', 'user_agent', 'created_at', DB::raw('COUNT(*) as visits'))
            ->groupBy('property_id', 'referer', 'user_agent', 'created_at')
            ->orderBy('created_at', 'desc')
            ->paginate(basicControl()->paginate);

        $parsedClicks = [];

        foreach ($clicks as $click) {
            $agent = new Agent();
            $agent->setUserAgent($click->user_agent);

            $parsedClicks[] = [
                'date'    => dateTime($click->created_at),
                'referer' => $click->referer ?? 'Direct',
                'device'  => $agent->isMobile() ? 'Mobile' : ($agent->isTablet() ? 'Tablet' : 'Desktop'),
                'os'      => $agent->platform() ?? 'Unknown',
                'browser' => $agent->browser() ?? 'Unknown',
                'visits'  => $click->visits,
                'propertyData' => $click->property ? [
                    'id'    => $click->property->id,
                    'title' => $click->property->title,
                    'slug'  => $click->property->slug,
                    'thumb' => getFile($click->property->photos->images['thumb']['driver'], $click->property->photos->images['thumb']['path']),
                    'price' => $click->property->pricing->nightly_rate ?? 0,
                ] : null,
            ];
        }

        $paginatedClicks = new LengthAwarePaginator(
            $parsedClicks,
            $clicks->total(),
            $clicks->perPage(),
            $clicks->currentPage(),
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return view(template() . 'affiliate.analytics', [
            'AffiliateClick' => $paginatedClicks,
            'totalVisitsThisWeek' => $totalVisitsThisWeek,
            'percentageChange' => $percentageChange,
            'uniqueVisitors' => $uniqueStats['uniqueVisitors'],
            'uniqueVisitorsChange' => $uniqueStats['percentageChange'],
            'avgSessionTime' => $formatTime($avgSessionThisWeek),
            'percentageChangeSession' => $percentageSessionChange,
            'avgBounce' => $bounceRateThisWeek,
            'percentageBounceChange' => $percentageBounceChange,
        ]);
    }
    public function getUniqueVisitorsStats($affiliateId)
    {
        $startOfThisWeek = Carbon::now()->startOfWeek()->toDateTimeString();
        $endOfThisWeek = Carbon::now()->endOfWeek()->toDateTimeString();

        $startOfLastWeek = Carbon::now()->subWeek()->startOfWeek()->toDateTimeString();
        $endOfLastWeek = Carbon::now()->subWeek()->endOfWeek()->toDateTimeString();

        $uniqueVisitors = AffiliateClick::where('affiliate_id', $affiliateId)
            ->selectRaw("
            COUNT(DISTINCT CASE WHEN created_at BETWEEN ? AND ? THEN ip END) AS unique_this_week,
            COUNT(DISTINCT CASE WHEN created_at BETWEEN ? AND ? THEN ip END) AS unique_last_week
        ", [$startOfThisWeek, $endOfThisWeek, $startOfLastWeek, $endOfLastWeek])
            ->first();

        $uniqueThisWeek = $uniqueVisitors->unique_this_week ?? 0;
        $uniqueLastWeek = $uniqueVisitors->unique_last_week ?? 0;

        if ($uniqueLastWeek == 0) {
            $percentageChange = $uniqueThisWeek > 0 ? 100 : 0;
        } else {
            $percentageChange = (($uniqueThisWeek - $uniqueLastWeek) / $uniqueLastWeek) * 100;
        }
        $percentageChange = round($percentageChange, 2);

        return [
            'uniqueVisitors' => $uniqueThisWeek,
            'percentageChange' => $percentageChange,
        ];
    }
    public function getAverageSessionTimeByPeriod($affiliateId, Carbon $startDate, Carbon $endDate)
    {
        $sessionTimeoutMinutes = 30;

        $clicks = AffiliateClick::where('affiliate_id', $affiliateId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('ip')
            ->orderBy('created_at')
            ->select('ip', 'created_at')
            ->get();

        $sessions = [];
        $currentSessionStart = null;
        $currentSessionEnd = null;
        $currentIp = null;

        foreach ($clicks as $click) {
            $clickTime = Carbon::parse($click->created_at);

            if ($currentIp !== $click->ip) {
                if ($currentSessionStart && $currentSessionEnd) {
                    $sessions[] = $currentSessionEnd->diffInSeconds($currentSessionStart);
                }
                $currentIp = $click->ip;
                $currentSessionStart = $clickTime;
                $currentSessionEnd = $clickTime;
                continue;
            }

            $diff = $clickTime->diffInMinutes($currentSessionEnd);

            if ($diff > $sessionTimeoutMinutes) {
                $sessions[] = $currentSessionEnd->diffInSeconds($currentSessionStart);
                $currentSessionStart = $clickTime;
            }

            $currentSessionEnd = $clickTime;
        }

        if ($currentSessionStart && $currentSessionEnd) {
            $sessions[] = $currentSessionEnd->diffInSeconds($currentSessionStart);
        }

        if (count($sessions) == 0) {
            return 0;
        }

        return array_sum($sessions) / count($sessions);
    }
    public function getBounceRate($affiliateId, $startDate, $endDate)
    {
        $clicks = AffiliateClick::where('affiliate_id', $affiliateId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->select('ip', DB::raw('COUNT(*) as visit_count'))
            ->groupBy('ip')
            ->get();

        $totalSessions = $clicks->count();
        $singlePageSessions = $clicks->where('visit_count', 1)->count();

        if ($totalSessions === 0) return 0;

        $bounceRate = ($singlePageSessions / $totalSessions) * 100;

        return round($bounceRate, 2);
    }
    public function fetchReferData(Request $request)
    {
        $range = $request->get('range', 'last7');

        $query = AffiliateClick::where('affiliate_id', auth('affiliate')->id());

        switch ($range) {
            case 'today':
                $query->whereDate('created_at', now());
                break;
            case 'last7':
                $query->where('created_at', '>=', now()->subDays(7));
                break;
            case 'last30':
                $query->where('created_at', '>=', now()->subDays(30));
                break;
            case 'thisYear':
                $query->whereYear('created_at', now()->year);
                break;
        }

        $clicks = $query->select('referer')->get();

        $refererCount = [];

        foreach ($clicks as $click) {
            $referer = $click->referer ?? 'Unknown';
            $refererCount[$referer] = ($refererCount[$referer] ?? 0) + 1;
        }

        arsort($refererCount);

        return response()->json([
            'labels' => array_keys($refererCount),
            'data' => array_values($refererCount),
        ]);
    }
    public function fetchCountryData(Request $request)
    {
        $limit = $request->get('limit');

        $clicks = AffiliateClick::where('affiliate_id', auth('affiliate')->id())->select('ip')->get();

        $countryCount = [];
        $isPrivateIp = function ($ip) {
            return filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) === false;
        };

        foreach ($clicks as $click) {
            if ($isPrivateIp($click->ip)) {
                $country = 'Private IP';
            } else {
                try {
                    $location = GeoIP::getLocation($click->ip);
                    $country = $location->country ?? 'Unknown';
                } catch (\Exception $e) {
                    $country = 'Unknown';
                }
            }
            $countryCount[$country] = ($countryCount[$country] ?? 0) + 1;
        }

        arsort($countryCount);

        if ($limit && is_numeric($limit)) {
            $countryCount = array_slice($countryCount, 0, (int)$limit, true);
        }

        return response()->json([
            'labels' => array_keys($countryCount),
            'data' => array_values($countryCount),
        ]);
    }

    public function payments(Request $request)
    {

        $data['payments'] = Transaction::where('affiliate_id', auth('affiliate')->id())
            ->orderBy('id', 'desc')
            ->when($request->transaction_id, fn($q, $trx) => $q->where('trx_id', $trx))
            ->when($request->datefilter, function ($q, $range) {
                $dates = explode(' - ', $range);
                if (count($dates) === 2) {
                    $start = \Carbon\Carbon::createFromFormat('d/m/Y', $dates[0])->startOfDay();
                    $end = \Carbon\Carbon::createFromFormat('d/m/Y', $dates[1])->endOfDay();
                    $q->whereBetween('created_at', [$start, $end]);
                }
            })
            ->paginate(basicControl()->paginate);

        return view(template() . 'affiliate.payments', $data);
    }

    public function downloadInvoice($id)
    {
        try {
            $transaction = Transaction::with(['affiliate'])->findOrFail($id);

            $logoPath = $this->getLogoPath();

            $pdf = PDF::loadView('email.transaction_download', [
                'transaction' => $transaction,
                'logoPath' => $logoPath,
                'businessInfo' => $this->getBusinessInfo(),
                'currencySymbol' => basicControl()->base_currency,
                'currentDate' => now()->format('F j, Y'),
            ]);

            $this->setPdfOptions($pdf);

            return $pdf->download("invoice-{$transaction->trx_id}.pdf");
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            abort(404, 'Transaction not found');
        } catch (\Exception $e) {
            \Log::error("Invoice generation failed for transaction {$id}: " . $e->getMessage());
            abort(500, 'Failed to generate invoice. Please try again later.');
        }
    }

    protected function getLogoPath()
    {
        if (!basicControl()->logo) {
            return null;
        }

        try {
            return Storage::disk(basicControl()->logo_driver)
                ->path(basicControl()->logo);
        } catch (\Exception $e) {
            \Log::warning("Logo path not accessible: " . $e->getMessage());
            return null;
        }
    }

    protected function getBusinessInfo()
    {
        $business = basicControl();

        return [
            'name' => config('app.name', $business->site_name ?? 'Our Business'),
            'address' => $business->address ?? 'Business Address',
            'contact_email' => filter_var($business->sender_email ?? null, FILTER_VALIDATE_EMAIL)
                ? $business->sender_email
                : 'support@example.com',
            'phone' => preg_replace('/[^0-9+]/', '', $business->contact_number ?? '+1234567890'),
        ];
    }
    protected function setPdfOptions($pdf)
    {
        $pdf->setOptions([
            'enable-local-file-access' => true,
            'images' => true,
            'encoding' => 'UTF-8',
            'viewport-size' => '1280x1024',
            'margin-top' => '10mm',
            'margin-bottom' => '10mm',
            'margin-left' => '10mm',
            'margin-right' => '10mm',
            'no-outline' => true,
            'disable-smart-shrinking' => true,
            'print-media-type' => true,
        ]);
    }

    public function pendingEarning(Request $request){

        $data['pendings'] = AffiliateEarning::with(['property.photos'])
            ->where('affiliate_id', auth('affiliate')->id())
            ->orderBy('id', 'desc')
            ->when($request->filled('property'), function ($query) use ($request) {
                $query->whereHas('property', function ($q) use ($request) {
                    $q->where('title', 'LIKE', '%' . $request->property . '%');
                });
            })
            ->when($request->datefilter, function ($q, $range) {
                $dates = explode(' - ', $range);
                if (count($dates) === 2) {
                    $start = \Carbon\Carbon::createFromFormat('d/m/Y', $dates[0])->startOfDay();
                    $end = \Carbon\Carbon::createFromFormat('d/m/Y', $dates[1])->endOfDay();
                    $q->whereBetween('created_at', [$start, $end]);
                }
            })
            ->where('status', '!=', null)
            ->get();
        return view(template() . 'affiliate.pending_earning', $data);
    }

}
