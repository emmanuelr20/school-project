<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Poll extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        //
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        //
    ];

    public function department()
    {
        return $this->belongsTo('App\Models\Department');
    }

    public function faculty()
    {
        return $this->belongsTo('App\Models\Faculty');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function role()
    {
        return $this->belongsTo('App\Models\Role');
    }

    public function comments()
    {
        return $this->hasMany('App\Models\Comment');
    }

    public function options()
    {
        return $this->hasMany('App\Models\Option');
    }

    public function totalVotes()
    {
        $total = 0;
        foreach ($this->options as $option) {
            $total += $option->votes->count();
        }
        return $total;
    }
}
