<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProviderBill extends Model
{
    use HasFactory;

    protected $fillable = [
        'provider_id',
        'cost',
    ];

    public function provider()
    {
        return $this->belongsTo(Provider::class);
    }
}
