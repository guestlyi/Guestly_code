<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Chat extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    public function user(){
        return $this->belongsTo(User::class, 'user_id');;
    }
    public function property(){
        return $this->belongsTo(Property::class, 'property_id');;
    }
    public function booking(){
        return $this->belongsTo(Booking::class, 'booking_uid');
    }
    public function sender(){
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver(){
        return $this->belongsTo(User::class, 'receiver_id');
    }

    public function reply(){
        return $this->hasMany(Chat::class, 'chat_id');
    }

    public function attachment(){
        return $this->hasMany(Chat::class, 'messsage_id');
    }
}
