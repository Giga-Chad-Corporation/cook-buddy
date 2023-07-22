<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Provider extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'provider_type_id',
    ];

    protected $attributes = [
        'revenue' => 0,
    ];


    public function providerType()
    {
        return $this->belongsTo(ProviderType::class);
    }



    public function providerBills()
    {
        return $this->hasMany(ProviderBill::class);
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function lessons()
    {
        return $this->hasMany(Lesson::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'messages')
            ->withPivot('message_content')
            ->withTimestamps();
    }

    public function offers()
    {
        return $this->hasMany('App\Models\Offer');
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function quotes()
    {
        return $this->hasMany(Quote::class);
    }

    public function services()
    {
        return $this->belongsToMany(Service::class)->withPivot('commission');
    }


}
