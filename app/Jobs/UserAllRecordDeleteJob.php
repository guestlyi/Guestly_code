<?php

namespace App\Jobs;

use App\Models\SupportTicket;
use App\Models\User;
use App\Traits\Upload;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class UserAllRecordDeleteJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Upload;

    public $user;

    /**
     * Create a new job instance.
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        DB::table('deposits')->where('user_id', $this->user->id)->delete();
        DB::table('payouts')->where('user_id', $this->user->id)->delete();
        DB::table('transactions')->where('user_id', $this->user->id)->delete();
        DB::table('user_kycs')->where('user_id', $this->user->id)->delete();
        DB::table('user_logins')->where('user_id', $this->user->id)->delete();

        DB::table('activity_logs')->where('activityable_id', $this->user->id)
            ->where('activityable_type', User::class)->delete();
        DB::table('feedback')->where('user_id', $this->user->id)->delete();
        DB::table('properties')->where('host_id', $this->user->id)->delete();
        DB::table('reports')->where('user_id', $this->user->id)->delete();
        DB::table('reviews')->where('guest_id', $this->user->id)->orWhere('host_id', $this->user->id)->delete();
        DB::table('taxes')->where('host_id', $this->user->id)->delete();
        DB::table('user_relatives')->where('user_id', $this->user->id)->delete();
        DB::table('wishlists')->where('user_id', $this->user->id)->delete();

        SupportTicket::where('user_id', $this->user->id)->get()->map(function ($item) {
            $item->messages()->get()->map(function ($message) {
                if (count($message->attachments) > 0) {
                    foreach ($message->attachments as $img) {
                        $this->fileDelete($img->driver, $img->file);
                        $img->delete();
                    }
                }
            });
            $item->messages()->delete();
            $item->delete();
        });


        DB::table('in_app_notifications')->where('in_app_notificationable_id', $this->user->id)->where('in_app_notificationable_type', 'App\Models\User')->delete();
    }
}
