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

    public function subscribers()
    {
        return $this->hasMany(Get::class, 'courses_id');
    }

    public function teach()
{
    return $this->hasMany(Teach::class, 'courses_id');
}


}
