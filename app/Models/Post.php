<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'body',
        'published_at',
        'image',
        'caption',
        'is_announcement', // <-- tambahkan ini
    ];

    protected $dates = ['published_at'];

    // owner relation
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function comments()
{
    return $this->hasMany(Comment::class)->latest();
}

public function likes()
{
    return $this->hasMany(Like::class);
}

// helper to get likes count quickly
public function likesCount()
{
    return $this->likes()->count();
}
}
