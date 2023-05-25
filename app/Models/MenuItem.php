<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    use HasFactory;

    protected $fillable = ['cost'];

    public function foodOrders()
    {
        return $this->belongsToMany(FoodOrder::class)->withPivot('quantity');
    }

    public function lesson()
    {
        return $this->hasOne('App\Models\Lesson');
    }

}
