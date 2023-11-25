<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Log;
class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'gender',
        'role',
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
    ];
    public static function boot(){
        parent::boot();
        /**
         * creating event log
         */
        static::creating(function($user){
            Log::info('creating even call'.$user );
        });
        /**
         * created event log 
         */
        static::created(function ($user){
            //crated event loging here 
            $user->sendEmailVerificationNotification();
            Log::info('Following user has been created'.$user);
        });
        static::updated(function($user){
            //update event loging here
            Log::info('Folloing user updated'.$user);
        });
        static::deleted(function($user){
            //delete event loging here 
            Log::info('Following user has been deted'.$user);
        });

    }
}
