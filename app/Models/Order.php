<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    // protected $fillable=[
    //     'user_id',
    //     'status',
    //     'payement',
    //     'quantity',
    //     'total'
    // ];
    protected $guarded = [];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
public function products()
{
    return $this->hasMany(Product::class);
}
}