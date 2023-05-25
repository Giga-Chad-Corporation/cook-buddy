<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function services()
    {
        return $this->belongsToMany(Service::class);
    }

    public function lessons()
    {
        return $this->belongsToMany('App\Models\Lesson', 'lesson_tag');
    }

    public function items()
    {
        return $this->belongsToMany(Item::class);
    }

}
