<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Provider extends Model
{
    use HasFactory;

    protected $fillable = [
        'hourly_cost',
    ];

    public function providerType()
    {
        return $this->belongsTo(ProviderType::class);
    }

    public function services()
    {
        return $this->hasMany(Service::class);
    }

    public function providerBills()
    {
        return $this->hasMany(ProviderBill::class);
    }

}
