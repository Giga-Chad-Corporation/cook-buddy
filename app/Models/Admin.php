<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    use HasFactory;

    protected $fillable = [
        'last_name',
        'first_name',
        'email',
        'password',
        'username',
        'is_moderator',
        'is_structure_administrator',
        'is_provider_administrator',
    ];

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    public function reportTickets()
    {
        return $this->hasMany(ReportTicket::class);
    }

}
