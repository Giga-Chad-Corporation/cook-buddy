<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IngredientType extends Model
{
    use HasFactory;

    protected $fillable = ['type_name'];

    public function ingredients()
    {
        return $this->hasMany(Ingredient::class, 'type_id');
    }
}

