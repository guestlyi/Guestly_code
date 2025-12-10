<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Traits\Notify;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Cache;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes, Notify;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = ['id'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $appends = ['last-seen-activity'];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    protected $dates = ['deleted_at'];

    protected static function boot()
    {
        parent::boot();
        static::saved(function () {
            Cache::forget('userRecord');
        });
    }

    public function funds()
    {
        return $this->hasMany(Fund::class)->latest()->where('status', '!=', 0);
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class, 'user_id');
    }

    public function transaction()
    {
        return $this->hasOne(Transaction::class)->latest();
    }

    public function payout()
    {
        return $this->hasMany(Payout::class, 'user_id');
    }
    public function booking()
    {
        return $this->hasMany(Booking::class, 'guest_id');
    }
    public function activeBooking()
    {
        return $this->hasMany(Booking::class, 'guest_id')->where('status', '!=', 0);
    }
    public function reviews()
    {
        return $this->hasMany(Review::class, 'guest_id')->where('review_id', null);
    }

    public function properties()
    {
        return $this->hasMany(Property::class, 'host_id');
    }
    public function activeProperties()
    {
        return $this->hasMany(Property::class, 'host_id')->where('status', 1);
    }

    public function getLastSeenActivityAttribute()
    {
        if (Cache::has('user-is-online-' . $this->id) == true) {
            return true;
        } else {
            return false;
        }
    }


    public function inAppNotification()
    {
        return $this->morphOne(InAppNotification::class, 'inAppNotificationable', 'in_app_notificationable_type', 'in_app_notificationable_id');
    }

    public function fireBaseToken()
    {
        return $this->morphMany(FireBaseToken::class, 'tokenable');
    }


    public function profilePicture()
    {
        $activeStatus = $this->LastSeenActivity === false ? 'warning' : 'success';
        $firstName = $this->firstname;
        $firstLetter = $this->firstLetter($firstName);
        if (!$this->image) {
            return $this->getInitialsAvatar($firstLetter, $activeStatus);
        } else {
            $url = getFile($this->image_driver, $this->image);
            return $this->getImageAvatar($url, $activeStatus);
        }
    }

    protected function firstLetter($firstName)
    {
        if (is_string($firstName)) {
            $firstName = mb_convert_encoding($firstName, 'UTF-8', 'auto');
        } else {
            $firstName = '';
        }
        $firstLetter = !empty($firstName) ? substr($firstName, 0, 1) : '';

        if (!mb_check_encoding($firstLetter, 'UTF-8')) {
            $firstLetter = '';
        }
        return $firstLetter;
    }
    private function getInitialsAvatar($initial, $activeStatus)
    {
        return <<<HTML
                <div class="avatar avatar-sm avatar-soft-primary avatar-circle">
                    <span class="avatar-initials">{$initial}</span>
                    <span class="avatar-status avatar-sm-status avatar-status-{$activeStatus}"></span>
                </div>
                HTML;
    }

    private function getImageAvatar($url, $activeStatus)
    {
        return <<<HTML
            <div class="avatar avatar-sm avatar-circle">
                <img class="avatar-img" src="{$url}" alt="Image Description">
                <span class="avatar-status avatar-sm-status avatar-status-{$activeStatus}"></span>
            </div>
            HTML;
    }

    public function sendPasswordResetNotification($token)
    {
        $this->mail($this, 'PASSWORD_RESET', $params = [
            'message' => '<a href="' . url('password/reset', $token) . '?email=' . $this->email . '" target="_blank">Click To Reset Password</a>'
        ]);
    }
    public function notifypermission()
    {
        return $this->morphOne(NotificationSettings::class, 'notifyable');
    }

    public function vendorInfo()
    {
        return $this->hasOne(VendorInfo::class, 'vendor_id');
    }
    public function taxes()
    {
        return $this->hasOne(Tax::class, 'host_id');
    }

    public function badge(){
        return $this->hasMany(UserBadge::class, 'user_id')->where('status', 1);
    }

    public function activatedBadge()
    {
        return $this->hasMany(UserBadge::class, 'user_id')
            ->where('status', 1)
            ->latest()
            ->with('badge')
            ->first();
    }

    public function feedbacks(){
        return $this->hasMany(Feedback::class, 'user_id');
    }
    public function reports(){
        return $this->hasMany(Report::class, 'user_id');
    }
    public function hostReview(){
        return $this->hasMany(Review::class, 'host_id')->where('review_id', null);
    }
    public function country()
    {
        return $this->hasOne(Country::class, 'name', 'country');
    }
    public function state()
    {
        return $this->hasOne(State::class, 'name', 'state');
    }
    public function city()
    {
        return $this->hasOne(City::class, 'name', 'city');
    }
    public function allRelatives()
    {
        return $this->hasOne(UserRelative::class, 'user_id');
    }
    public function language()
    {
        return $this->belongsTo(Language::class, 'language_id');
    }
}
