<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ValidationKey extends Model
{
    use HasFactory;

    protected $table = 'validation_key';

    protected $fillable = [
        'key',
        'user_id',
    ];

    public function isExpire()
    {
        if (now() > $this->updated_at->addMinutes(10)) {
            $this->delete();
            return true;
        }
        return false;
    }
}
