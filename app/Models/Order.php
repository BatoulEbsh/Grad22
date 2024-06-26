<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Order extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'image',
        'desc',
        'totalVoltage',
        'chargeHours',
        'location',
        'type_id',
        'user_id',
    ];

    protected $hidden = [

    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function products()
    {
        return $this->belongsToMany(Product::class, 'order_products') ->withPivot('amount');
    }
}
