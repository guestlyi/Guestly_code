<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Destination extends Model
{
    use HasFactory;
    protected $guarded = ['id'];


    protected $casts = [
        'place' => 'object'
    ];

    public function countryTake()
    {
        return $this->belongsTo(Country::class, 'country', 'id');
    }
    public function property()
    {
        return $this->hasMany(Property::class, 'destination_id');
    }

    public function stateTake()
    {
        return $this->belongsTo(State::class, 'state', 'id');
    }

    public function cityTake()
    {
        return $this->belongsTo(City::class, 'city', 'id');
    }
}
