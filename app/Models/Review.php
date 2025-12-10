<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $casts = [
        'rating' => 'array'
    ];

    public function property()
    {
        return $this->belongsTo(Property::class, 'property_id');
    }
    public function guest()
    {
        return $this->belongsTo(User::class, 'guest_id');
    }
    public function host()
    {
        return $this->belongsTo(User::class, 'host_id');
    }
    public function replies()
    {
        return $this->hasMany(Review::class, 'review_id');
    }
    public function activeReplies()
    {
        return $this->hasMany(Review::class, 'review_id')->where('status', 1);
    }
}
