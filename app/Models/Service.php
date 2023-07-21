<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'start_date_time',
        'end_date_time',
        'cost',
    ];

    public function provider()
    {
        return $this->belongsTo(Provider::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function serviceType()
    {
        return $this->belongsTo(ServiceType::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function offers()
    {
        return $this->hasMany('App\Models\Offer');
    }

    public function buildings()
    {
        return $this->belongsToMany(Building::class, 'service_building');
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function quotes()
    {
        return $this->hasMany(Quote::class);
    }


}
