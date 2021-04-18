<?php

namespace App;

// use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
// use Spatie\Activitylog\Traits\CausesActivity;

class User extends Authenticatable
{
    // use Notifiable, CausesActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'username', 'email', 'password', 'position', 'status'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
    * The attributes that should be cast to native types.
    *
    * @var array
    */
    protected $casts = [
        'status' => 'boolean',
    ];

    /**
     * The roles that belong to the user.
     */
    public function roles()
    {
        return $this->belongsToMany('App\Role')->withTimestamps();
    }


    /**
     * Checks if the user has the given role.
     *
     * @param string $alias
     * @return bool
     */
    public function hasRole($alias)
    {
        if(!is_array($alias)){
            $alias = [$alias];
        }

        return $this->roles()->whereIn('alias', $alias)->exists() ? true : false;
    }

    /**
     * Checks if the user is Super Admin.
     */
    public function isSuperAdmin()
    {
        return $this->roles()->where('alias', 'root')->exists() ? true : false;
    }

    /**
     * Get user's first name.
     */
    public function getFirstNameAttribute()
    {
        return explode(' ', $this->name)[0];
    }

    /**
     * Get the User's friendly status based on status.
     */
    public function getStatusContextAttribute()
    {
      return $this->status ? '' : 'active';
    }

    /**
     * Scope a query to return users subscribed on given $notifications
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param array $notifications
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSubscribedTo($query, array $notifications)
    {
        return $query->whereHas('notifications', function($query) use($notifications) {
            $query->whereIn('alias', $notifications);
        });
    }
}
