<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Page extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function seoable()
    {
        return $this->morphOne(Seo::class, 'seoable');
    }
    public function details()
    {
        return $this->hasOne(PageDetail::class, 'page_id', 'id');
    }

    public function getLanguageEditClass($id, $languageId){
        return DB::table('page_details')->where(['page_id' => $id, 'language_id' => $languageId])->exists() ? 'bi-check2' : 'bi-pencil';
    }


}
