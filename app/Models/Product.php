<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Product extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'image',
        'price',
        'available',
        'disc',
        'quantity',
        'category_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [

    ];

    public function proposedSystems()
    {
        return $this->belongsToMany(ProposedSystem::class, 'proposed_system_product');
    }
    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_products') ->withPivot('amount');
    }
    public function category()
    {
        return $this->belongsTo(Category::class,'category_id');
    }
}
