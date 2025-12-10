<?php

namespace App\Console\Commands;

use App\Models\Affiliate;
use Carbon\Carbon;
use Illuminate\Console\Command;

class PermanentlyDeleteOldAffiliates extends Command
{
    protected $signature = 'affiliates:purge-old-deleted';
    protected $description = 'Permanently delete soft-deleted affiliates older than 15 days';

    public function handle()
    {
        $cutoffDate = Carbon::now()->subDays(15);
        $oldDeletedAffiliates = Affiliate::onlyTrashed()
            ->where('deleted_at', '<=', $cutoffDate)
            ->get();

        foreach ($oldDeletedAffiliates as $affiliate) {
            $affiliate->forceDelete();
        }

    }
}
