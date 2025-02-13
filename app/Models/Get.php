<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Get extends Model
{
    use HasFactory;

    protected $table = 'get';


    protected $fillable = [
        'user_id',
        'courses_id',
        'phone',
        'image'
    ];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'courses_id');
    }
}
