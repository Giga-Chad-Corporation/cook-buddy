<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'last_name',
        'first_name',
        'email',
        'password',
        'profile_photo_path',
        'api_token',
        'address',
        'phone',
        'description',
    ];


    public function services()
    {
        return $this->belongsToMany(Service::class);
    }
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
    public function provider()
    {
        return $this->hasOne(Provider::class);
    }


    public function reviewedServices()
    {
        return $this->belongsToMany(Service::class, 'reviews')
            ->withPivot('content', 'rate', 'picture_url')
            ->withTimestamps();
    }

    public function providers()
    {
        return $this->belongsToMany(Provider::class, 'messages')
            ->withPivot('message_content')
            ->withTimestamps();
    }

    public function relatedUsers()
    {
        return $this->belongsToMany(User::class, 'relation_table')
            ->withPivot('content', 'rate', 'picture_url')
            ->withTimestamps();
    }
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [

    ];



}
