<?php

namespace App\Models;

use App\Traits\HasRolesAndPermissions;
use App\Traits\Uuid;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Permission;
use App\Models\Role;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRolesAndPermissions, Uuid;

    public $incrementing = false;

    protected $keyType = 'uuid';
    
    protected $fillable = [
        'id',
        'first_name',
        'last_name',
        'birth_date',
        'sex',
        'cpf',
        'money',
        'email',
        'mobile_phone',
        'username',
        'email_verified_at',
        'password',
        'currency',
        'language',
        'language_label',
        'address_country',
        'timezone',
        'address_zipcode',
        'address_street',
        'address_number',
        'address_neighborhood',
        'address_complement',
        'address_city_id',
        'address_city',
        'address_state_id',
        'address_state',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function role(){
        return $this->belongsToMany(Role::class, 'users_roles')->with('permissions');
    }

    public function permissions(){
        return $this->belongsToMany(Permission::class, 'users_permissions');
    }

    public function hasPermission(Permission $permission){
        foreach ($this->permissions as $item) {
            if($item->name == $permission->name)
                return true;
        }
        return $this->hasAnyRoles($permission->roles);
    }

    public function hasAnyRoles($roles){
        if(is_array($roles) || is_object($roles)){
            return !! $roles->intersect($this->role)->count();
        }

        return $this->role->contains('name', $roles);
    }
}
