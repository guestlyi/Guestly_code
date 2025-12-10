<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertyAmenity extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $casts = [
        'amenities' => 'array',
    ];

    public function property()
    {
        return $this->belongsTo(Property::class, 'property_id');
    }
    public function amenity()
    {
        return $this->belongsTo(Amenity::class, 'amenity_id');
    }
}
