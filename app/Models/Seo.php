<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seo extends Model
{
    use HasFactory;
    protected $guarded =['id'];

    protected $casts = [
        'meta_keywords' => 'array'
    ];

    public function seoable()
    {
        return $this->morphTo();
    }

    protected function metaKeywords(): Attribute
    {
        return Attribute::make(
            get: fn(string $value) => explode(", " , $value),
        );
    }
    public function metaRobots()
    {
        $cleaned = str_replace(['[', ']', '"'], '', $this->meta_robots);
        return explode(",", $cleaned);
    }

    public function getMetaRobotAttribute()
    {
        $cleaned = str_replace(['[', ']', '"'], '', $this->meta_robots);
        return $cleaned;
    }
}
