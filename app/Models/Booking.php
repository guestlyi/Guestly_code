<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Booking extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'information' => 'array',
        'applied_discount' => 'array',
        'user_info' => 'array'
    ];
    protected static function booted(): void
    {
        static::creating(function (Booking $booking) {
            $booking->uid = 'B' . Str::upper(Str::random(9));
        });
    }

    public function property()
    {
        return $this->belongsTo(Property::class, 'property_id');
    }
    public function guest()
    {
        return $this->belongsTo(User::class, 'guest_id');
    }

    public function chat(){
        return $this->hasOne(Chat::class, 'booking_uid');
    }
    public function depositable()
    {
        return $this->morphOne(Deposit::class, 'depositable');
    }

    public function affiliate_user()
    {
        return $this->belongsTo(Affiliate::class, 'affiliate_user_id');
    }
}
