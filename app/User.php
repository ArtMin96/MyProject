<?php

namespace App;

use App\Models\UserAddress;
use App\Models\CardBinding;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name','last_name', 'email', 'password','role','phone','additional','accept_notification','roles'
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
        'email_verified_at' => 'datetime',
        'additional' => 'json',
        'roles' => 'array',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */

    public function address(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(UserAddress::class);
    }


    /**
     * @return array
     */
    public function getRoles():array
    {
        $roles = $this->getAttribute('roles');
        if (is_null($roles)) $roles = [];

        return $roles;
    }

    /**
     * @param array $roles
     * @return mixed
     */
    public function setRoles(array $roles)
    {
        $this->setAttribute('roles', $roles);

        return  $this;
    }

    /**
     * @param $role
     * @return mixed
     */
    public function addRole($role)
    {
        $roles = $this->getRoles();
        $roles[] = $role;
        $roles = array_unique($roles);
        $this->setRoles($roles);

        return $this;
    }

    /***
     * @param $role
     * @return mixed
     */
    public function hasRole($role):bool
    {
        return in_array($role, $this->getRoles());
    }


    public function hasRoles(array $roles):bool
    {
        $currentRoles = $this->getRoles();
        foreach($currentRoles as $role) {
            if(in_array($role, $roles )) return true ;
        }
        return false;
    }

    public function cards()
    {
        return $this->hasMany(CardBinding::class);
    }

}
