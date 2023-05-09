<?php

namespace App\Models;

use App\Traits\SerializeDateTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WhatNew extends Model
{
    use HasFactory, SerializeDateTrait;

    protected $fillable = ['title', 'content','read','message_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
