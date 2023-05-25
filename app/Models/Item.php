<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'model_name',
        'buying_price',
        'selling_price',
    ];

    public function itemOrders()
    {
        return $this->belongsToMany(ItemOrder::class)->withPivot('quantity');
    }

    public function itemType()
    {
        return $this->belongsTo(ItemType::class);
    }

    public function buildings()
    {
        return $this->belongsToMany(Building::class)->withPivot('quantity');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }
}
