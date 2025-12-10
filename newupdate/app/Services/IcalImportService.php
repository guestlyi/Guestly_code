<?php

namespace App\Services;

use App\Models\Property;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class IcalImportService
{
    /**
     * Import ICS/ICAL from external URL and block dates for the property
     *
     * @param string $url
     * @param Property $property
     * @param string $source
     * @return void
     */
    public function import(string $url, Property $property, string $source = 'external')
    {
        if (!$url) return;

        try {
            $response = Http::get($url);
            if ($response->status() !== 200) return;

            $ics = $response->body();
            preg_match_all('/BEGIN:VEVENT(.*?)END:VEVENT/s', $ics, $matches);

            foreach ($matches[1] as $eventRaw) {

                if (!preg_match('/DTSTART(?:;[^:]+)?:([0-9TZ\-]+)/', $eventRaw, $startMatch)) continue;
                if (!preg_match('/DTEND(?:;[^:]+)?:([0-9TZ\-]+)/', $eventRaw, $endMatch)) continue;

                $checkIn  = $this->parseDate($startMatch[1]);
                $checkOut = $this->parseDate($endMatch[1]);

                if (strlen($startMatch[1]) == 8) $checkOut = $checkOut->subDay();

                $siteSlug = Str::slug(basicControl()->site_title ?? 'RentalSpace');
                $domain   = parse_url(config('app.url'), PHP_URL_HOST) ?? 'localhost';

                $uid = preg_match('/UID:(.+)/', $eventRaw, $uidMatch)
                    ? trim($uidMatch[1])
                    : $siteSlug.'-'.$property->id.'-'.md5($checkIn.$checkOut).'@'.$domain;

                Booking::updateOrCreate(
                    ['property_id' => $property->id, 'external_calendar_uid' => $uid],
                    [
                        'check_in_date'   => $checkIn->toDateString(),
                        'check_out_date'  => $checkOut->toDateString(),
                        'status'          => 1,
                        'is_external'     => true,
                        'external_source' => $source,
                        'external_remark'     => 'Blocked via external calendar import',
                    ]
                );
            }

        } catch (\Exception $e) {
            Log::error("ICS import error for property {$property->id}: ".$e->getMessage());
        }
    }

    /**
     * Parse ICS date strings into Carbon instances
     *
     * @param string $s
     * @return Carbon
     */
    private function parseDate(string $s): Carbon
    {
        if (preg_match('/^\d{8}$/', $s)) return Carbon::createFromFormat('Ymd', $s)->startOfDay();

        if (preg_match('/^(\d{8}T\d{6})Z$/', $s, $m)) {
            return Carbon::createFromFormat('Ymd\THis', $m[1], 'UTC')
                ->setTimezone(config('app.timezone'));
        }

        return Carbon::createFromFormat('Ymd\THis', $s);
    }
}
