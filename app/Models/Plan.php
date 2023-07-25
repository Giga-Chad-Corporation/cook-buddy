<?php

namespace App\Models;

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'monthly_price',
        'annual_price'
    ];


    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }
}
