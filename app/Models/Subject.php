<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subject extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'subjects';
    protected $fillable = [
        'id',
        'name',
    ];

    public function services()
    {
        return $this->hasOne(Service::class);
    }
}
