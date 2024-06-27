<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Record extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id ',
        'order_id',
        'desc'
    ];
    public $timestamps = true;
    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
    public function order()
    {
        return $this->belongsTo(Order::class,'order_id');
    }
    public function appointment()
    {
        return $this->hasOne(Appointment::class, 'order_id', 'order_id');
    }

}
