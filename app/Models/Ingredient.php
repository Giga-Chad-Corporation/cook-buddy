<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function lessons()
    {
        return $this->belongsToMany(Lesson::class)->withPivot('quantity');
    }

    public function ingredientType()
    {
        return $this->belongsTo(IngredientType::class, 'type_id');
    }

    public function buildings()
    {
        return $this->belongsToMany(Building::class)->withPivot('quantity');
    }
    public function items()
    {
        return $this->belongsToMany(Item::class, 'item_ingredient');
    }
}
