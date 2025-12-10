<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Amenity extends Model
{
    use HasFactory;

    protected $guarded = ['id'];


    public function propertyAmenity()
    {
        return PropertyAmenity::query()
            ->where(function ($query) {
                $query->whereJsonContains('amenities->amenity', (string) $this->id)
                    ->orWhereJsonContains('amenities->favourites', (string) $this->id)
                    ->orWhereJsonContains('amenities->safety_item', (string) $this->id);
            });
    }

}
