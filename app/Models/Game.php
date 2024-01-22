<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasFactory;
    protected $table = 'game';
    protected $fillable = [
        'title',
        'platform',
        'price',
        'discount',
        'video',
        'summary',
        'cover',
        'wallpaper',
        'screenshots',
        'releaseDate',
        'slug',
    ];
}
