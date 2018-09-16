<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'is_suspended', 'is_active'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    public function roles()
    {
        return $this->belongsToMany('App\Models\Role');
    }

    public function isAdmin($user=null)
    {
        if (!$user){
            if(!\Auth::check()) return false;
            $role = \Auth::user()->roles()->whereIn('name', ['admin', 'superAdmin'])->get();
        } else {
            $role = $user->roles()->whereIn('name', ['admin', 'superAdmin'])->get(); 
        }
        if ($role->count()) return true;
        return false;
    }

    public function isSuperAdmin($user=null)
    {
        if (!$user){
            if(!\Auth::check()) return false;
            $role = \Auth::user()->roles()->where('name', 'superAdmin')->get();
        } else {
            $role = $user->roles()->where('name', 'superAdmin')->get();
        } 
        if ($role->count()) return true;
        return false;
    }

    public function highestAccessLevel($user= null)
    {
        if (!$user){
            if(!\Auth::check()) return false;
            $role = \Auth::user()->roles()->orderBy('access_level')->first();
        } else {
            $role = $user->roles()->orderBy('access_level')->first();
        }
        if ($role === null) return null;
        return $role->access_level;
    }

    public function getFullName()
    {
        return ucwords($this->first_name . ' ' .$this->last_name);
    }

    public function posts()
    {
        return $this->hasMany('App\Models\Post');
    }

    public function polls()
    {
        return $this->hasMany('App\Models\Poll');
    }

    public function notifications()
    {
        return $this->hasMany('App\Models\Notification');
    }

    public function department()
    {
        return $this->belongsTo('App\Models\Department');
    }

    public function faculty()
    {
        return $this->belongsTo('App\Models\Faculty');
    }
    public function votes()
    {
        return $this->hasMany('App\Nodels\Vote');
    }

    public function comments()
    {
        return $this->hasMany('App\Nodels\Comment');
    }
}
