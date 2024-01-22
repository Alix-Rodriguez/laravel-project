<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class platform extends Model
{   
    protected $table = 'platforms';
    protected $fillable = ['title', 'slug', 'icon_path', 'icon_filename'];
    use HasFactory;
}
