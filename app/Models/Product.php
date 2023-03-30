<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    // protected $guarded = [];
    protected $fillable = [
        'category_id',
        'name',
        'description',
        'quantity',
        'price',
        'image',
        'discount',
        'user_id'
    ];
    protected $with = ['category'];
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }
    public function cart()
    {
        return $this->hasOne(Cart::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function order(){
        return $this->belongsTo(Order::class);
    }
    // public function favorite()
    // {
    //     return $this->belongsTo(Favorite::class);
    // }

}