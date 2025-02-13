<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'image',
        'user_add_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_add_id');
    }
}
