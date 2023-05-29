<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Provider extends Model
{
    use HasFactory;

    protected $fillable = [
        'hourly_cost',
    ];

    public function providerType()
    {
        return $this->belongsTo(ProviderType::class);
    }

    public function services()
    {
        return $this->hasMany(Service::class);
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


}