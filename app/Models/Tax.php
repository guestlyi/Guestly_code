<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tax extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function host()
    {
        return $this->hasOne(User::class, 'host_id');
    }
}
