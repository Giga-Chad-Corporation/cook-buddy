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
        'price_month',
        'price_year'
    ];

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }
}
