<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AffiliateStatistics extends Model
{
    protected $guarded = ['id'];

    public function affiliate()
    {
        return $this->belongsTo(Affiliate::class, 'affiliate_id');
    }
    public function property()
    {
        return $this->belongsTo(Property::class, 'property_id');
    }
}
