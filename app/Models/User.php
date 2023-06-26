<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property int $id
 * @property string $username->unique()
 * @property string $email->unique()
 * @property string $email_verified_at->nullable()
 * @property string $first_name
 * @property string $last_name
 * @property string $password
 * @property string $phone->nullable()
 * @property string $description->nullable()
 * @property string $address->nullable()
 * @property string $api_token->unique()->nullable()->default(null)
 * @property string $profile_photo_path->nullable()
 * @property string $remember_token
 * @property string $created_at
 * @property string $updated_at
 */
class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

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
        'profile_photo_path',
    ];

    public function getProfilePhotoPathAttribute(): string
    {
        return $this->profile_photo_path ?? '';
    }
}
