<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
USE Illuminate\Contracts\Translation\HasLocalePreference;

class User extends \TCG\Voyager\Models\User
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];
    /* protected $fillable = [
        'name', 'email', 'password',
    ]; */

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
        'email_verified_at' => 'datetime',
    ];

    public function employee()
    {
        return $this->hasOne(Employee::class);
    }

    public function companiesResources()
    {
        return $this->hasMany(CompanyResource::class);
    }

    public function workersGte()
    {
        return $this->hasMany('App\Employee', 'jefe_id', 'id');
    }

    public function timetables()
    {
        return $this->hasMany('App\Timetable','created_by','id');
    }

    public function appcompany(){
        return $this->hasOne(AppSession::class);
    }
}
