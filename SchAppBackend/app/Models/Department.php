<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
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
        'created_at', 'updated_at'
    ];

    public function users()
    {
        return $this->hasMany('App\User');
    }

    public function faculty()
    {
        return $this->belongsTo('App\Models\Faculty');
    }

    public function head()
    {
        return $this->belongsTo('App\User', 'head');
    }

    public function posts()
    {
        return $this->hasMany('App\Models\Post');
    }

    public function polls()
    {
        return $this->hasMany('App\Models\Poll');
    }
}
