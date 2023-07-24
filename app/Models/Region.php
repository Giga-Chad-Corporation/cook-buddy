<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function providers()
    {
        return $this->belongsToMany(Provider::class)->withPivot('available_date', 'start_time', 'end_time')->withTimestamps();
    }
}
