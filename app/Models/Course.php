<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'contant',
        'description',
        'price',
        'time',
        'Reservations',
        'user_add_id',
        'image',
    ];
}
