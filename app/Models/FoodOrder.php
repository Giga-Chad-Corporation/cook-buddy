<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FoodOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'planned_delivery_date',
        'real_delivery_date',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
