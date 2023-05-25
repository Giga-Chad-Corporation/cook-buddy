<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportTicketType extends Model
{
    use HasFactory;

    protected $fillable = [
        'type_name',
    ];

    public function reportTickets()
    {
        return $this->hasMany(ReportTicket::class);
    }
}
