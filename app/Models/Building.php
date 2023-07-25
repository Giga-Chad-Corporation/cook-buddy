<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Building extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
    ];


    public function ingredients()
    {
        return $this->belongsToMany(Ingredient::class)->withPivot('quantity');
    }

    public function rooms()
    {
        return $this->hasMany(Room::class);
    }

    public function items()
    {
        return $this->belongsToMany(Item::class)->withPivot('quantity');
    }

    public function services()
    {
        return $this->belongsToMany(Service::class, 'service_building');
    }

    public function buildingType()
    {
        return $this->belongsTo(BuildingType::class);
    }
}
