<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProviderType extends Model
{
    use HasFactory;

    protected $fillable = [
        'type_name',
    ];

    public function providers()
    {
        return $this->hasMany(Provider::class);
    }
}

