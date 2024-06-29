<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProposedSystem extends Model
{
    use HasFactory;

    protected $fillable =
        [
            'name',
            'desc'
        ];


    public function products()
    {
        return $this->belongsToMany(Product::class, 'proposed_system_product')
            ->withPivot('amount');
    }

}
