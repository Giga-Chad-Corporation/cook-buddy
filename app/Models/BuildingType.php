<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BuildingType extends Model
{
    use HasFactory;

    protected $table = 'building_types';

    public function buildings()
    {
        return $this->hasMany(Building::class);
    }
}
