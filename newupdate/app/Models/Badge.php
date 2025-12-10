<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Badge extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'users' => 'array',
        'info' => 'array',
    ];

    public function vendorInfos()
    {
        return $this->hasMany(VendorInfo::class, 'badge');
    }
}
