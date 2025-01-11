<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lecturer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'notes',
        'user_add_id',
        'image',
    ];


    public function teach()
    {
        return $this->hasMany(Teach::class, 'lecturer_id');
    }

}
