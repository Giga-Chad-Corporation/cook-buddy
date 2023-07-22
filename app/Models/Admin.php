<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'first_name', 'last_name', 'email', 'password', 'is_super_admin', 'manage_admins', 'manage_users', 'manage_providers', 'manage_services', 'manage_plans'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
