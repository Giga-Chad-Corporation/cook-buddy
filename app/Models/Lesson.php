<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class  Lesson extends Model
{
    use HasFactory;

    protected $fillable = [
        'video_url',
        'thumbnail_url',
        'title',
    ];

    public function provider()
    {
        return $this->belongsTo(Provider::class);
    }

    public function lessonSections()
    {
        return $this->hasMany(LessonSection::class);
    }

    public function menuItem()
    {
        return $this->belongsTo('App\Models\MenuItem');
    }

    public function tags()
    {
        return $this->belongsToMany('App\Models\Tag', 'lesson_tag');
    }

    public function ingredients()
    {
        return $this->belongsToMany(Ingredient::class)->withPivot('quantity');
    }





}
