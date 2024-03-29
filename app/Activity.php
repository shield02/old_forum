<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $take;

    /**
     * Don't auto-apply any mass assignment protection.
     * 
     * @var array $guarded
     */
    protected $guarded = [];

    /**
     * Fetch the associated subject for the activity.
     * 
     * @return Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function subject()
    {
        return $this->morphTo();
    }

    /**
     * 
     * @param mixed $user
     * @param int $take
     * @return mixed
     */
    public static function feed($user, $take = 50) 
    {
        return static::where('user_id', $user->id)
        ->latest()
        ->with('subject')
        ->take($take)
        ->get()
        ->groupBy(function ($activity) {
            return $activity->created_at->format('Y-m-d');
        });
    }
}
