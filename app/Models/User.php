<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use Exception;
use Mail;
use App\Mail\ResetMail;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'city',
        'address',
        'phone',
        'description',
        'role',
        'status'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function services()
    {
        return $this->hasOne(Service::class);
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class, 'instructor_id');
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class, 'user_id');
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function generateCode(User $user)
    {
        $key = rand(100000, 999999);
  
        ValidationKey::updateOrCreate(
            [ 'user_id' => $user->id ],
            [ 'key' => $key ]
        );
    
        try {
  
            $details = [
                'title' => 'Mail from Learn App, reset password code.',
                'key' => $key
            ];
             
            Mail::to($user->email)->send(new ResetMail($details));
    
        } catch (Exception $e) {
            info("Error: ". $e->getMessage());
        }
    }
}
