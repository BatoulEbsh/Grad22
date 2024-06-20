<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoices extends Model
{
    use HasFactory;

    protected $fillable = [
        'totalPrice',
        'date',
        'order_id'
    ];
}
