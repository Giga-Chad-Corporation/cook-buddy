<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'url',
        'is_valid'
    ];

    public function provider()
    {
        return $this->belongsTo(Provider::class);
    }

    public function documentType()
    {
        return $this->belongsTo(DocumentType::class);
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }



}
