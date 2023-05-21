<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceType extends Model
{
    use HasFactory;

    /**
     * Get the services for the service type.
     */
    public function services()
    {
        return $this->hasMany(Service::class);
    }
}

