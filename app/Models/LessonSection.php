<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LessonSection extends Model
{
    use HasFactory;

    protected $fillable =
        ['content',
        'picture_url',
        'title'];

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }
}
