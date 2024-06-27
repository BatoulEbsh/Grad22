<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $fillable = [
        'start_time',
        'end_time',
        'team_id',
        'order_id',
        'user_id',
        'type_id',
        'status_id'];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }

}
