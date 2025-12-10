<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Property;
use App\Services\IcalImportService;

class SyncPropertyIcal extends Command
{
    protected $signature = 'ical:sync';
    protected $description = 'Sync all property ICS calendars';

    protected $icalService;

    public function __construct(IcalImportService $icalService)
    {
        parent::__construct();
        $this->icalService = $icalService;
    }

    public function handle()
    {
        $properties = Property::with('icalSources')->get();

        foreach ($properties as $property) {
            foreach ($property->icalSources as $source) {
                $this->icalService->import($source->ical_url, $property, $source->source_name);
                $this->info("Synced {$property->id} from {$source->source_name}");
            }
        }

        $this->info('All property ICS calendars synced!');
    }
}
