<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'last_name',
        'first_name',
        'email',
        'password',
        'phone',
        'username',
        'description',
        'address',
    ];

    public function services()
    {
        return $this->belongsToMany(Service::class);
    }
    public function food_orders()
    {
        return $this->hasMany(FoodOrder::class);
    }
    public function provider()
    {
        return $this->hasOne(Provider::class);
    }

    public function reportTickets()
    {
        return $this->hasMany(ReportTicket::class);
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

    public function regions()
    {
        return $this->hasMany(Region::class);
    }

    public function relatedUsers()
    {
        return $this->belongsToMany(User::class, 'relation_table')
            ->withPivot('content', 'rate', 'picture_url')
            ->withTimestamps();
    }



}
