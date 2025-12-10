<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertyFeature extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $casts = [
        'others' => 'array'
    ];

    public function property()
    {
        return $this->belongsTo(Property::class, 'property_id');
    }
}
