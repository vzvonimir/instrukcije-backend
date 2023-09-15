<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rating extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'ratings';
    protected $fillable = [
        'user_id',
        'instructor_id',
        'rating',
    ];

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
