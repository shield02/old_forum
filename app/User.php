<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'email',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the route key name for Laravel.
     * 
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'name'; //username
    }

    /**
     * Fetch all threads that were created by the user.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function threads()
    {
        return $this->hasMany(Thread::class)->latest();
    }

    /**
     * 
     * 
     */
    public function lastReply()
    {
        return $this->hasOne(Reply::class)->latest();
    }

    /**
     * Fetch all the activities created by the user.
     * 
     * @return Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function activity()
    {
        return $this->hasMany(Activity::class);
    }

    /**
     * 
     * 
     * @param mixed $thread
     */
    public function read($thread)
    {
        cache()->forever(
            $this->visitedThreadCacheKey($thread), 
            \Carbon\Carbon::now()
        );
    }

    /**
     * Identifier cache key
     * 
     * @param mixed $thread
     * @return string
     */
    public function visitedThreadCacheKey($thread)
    {
        return sprintf("users.%s.visits.%s", $this->id, $thread->id);
    }
}
