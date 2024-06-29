<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoices extends Model
{
    use HasFactory;

    protected $fillable = [
        'totalPrice',
        'date',
        'order_id',
        'otherPrice'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
