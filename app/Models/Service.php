<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'services';

    protected $fillable = [
        'price',
        'description',
        'instructor_id',
        'subject_id',
        'category_id',
    ];

    public function instructors()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public function subjects()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    public function categories()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}
