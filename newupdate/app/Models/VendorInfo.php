<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorInfo extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'skills' => 'object',
    ];
    protected $appends = ['active_years'];

    public function user()
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }
    public function badgeInfo()
    {
        return $this->hasOne(Badge::class, 'badge');
    }
    public function getActiveYearsAttribute()
    {
        return round(\Carbon\Carbon::parse($this->created_at)->diffInDays(now()) / 365, 1);
    }
}
