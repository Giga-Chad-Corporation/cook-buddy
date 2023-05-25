<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportTicket extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'content_type',
        'content_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }


}
