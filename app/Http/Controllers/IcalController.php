<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Services\IcalImportService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class IcalController extends Controller
{
    public function export(Request $request, Property $property)
    {
        $token = $request->query('token');

        if (!$token || $property->ical_export_token !== $token) {
            abort(403, 'Invalid token');
        }

        $bookings = $property->bookings()->whereIn('status',[1,4])->get();

        $siteTitle = basicControl()->site_title ?? 'RentalSpace';

        $lines = [
            'BEGIN:VCALENDAR',
            'VERSION:2.0',
            'PRODID:-//'.$siteTitle.'//'.$siteTitle.' Calendar//EN'
        ];

        foreach ($bookings as $b) {
            $start = Carbon::parse($b->check_in_date);
            $end   = Carbon::parse($b->check_out_date)->addDay();

            $siteSlug = Str::slug(basicControl()->site_title ?? 'RentalSpace');
            $uid = $b->external_calendar_uid ?? $siteSlug.'-'.$b->id.'@'.parse_url(config('app.url'), PHP_URL_HOST);

            $lines[] = 'BEGIN:VEVENT';
            $lines[] = 'UID:'.$uid;
            $lines[] = 'DTSTAMP:'.$b->created_at->format('Ymd\THis\Z');
            $lines[] = 'DTSTART;VALUE=DATE:'.$start->format('Ymd');
            $lines[] = 'DTEND;VALUE=DATE:'.$end->format('Ymd');
            $lines[] = 'SUMMARY:Booked';
            $lines[] = 'END:VEVENT';
        }

        $lines[] = 'END:VCALENDAR';

        return response(implode("\r\n",$lines))
            ->header('Content-Type','text/calendar; charset=utf-8')
            ->header('Content-Disposition','inline; filename="calendar.ics"');
    }

    public function refresh(Property $property, IcalImportService $icalService)
    {
        foreach ($property->icalSources as $source) {
            $icalService->import($source->ical_url, $property, $source->source_name);
        }

        return redirect()->back()->with('success', 'Property ICS calendars refreshed!');
    }
}
