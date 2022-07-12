<?php

namespace App\Models;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Permission;

class Role extends Model
{
    use HasFactory, Uuid;

    protected $table = 'roles';

    public $incrementing = false;

    protected $keyType = 'uuid';

    protected $fillable = [
        'id',
        'name',
        'slug',
    ];

    public function allRolePermissions(){
    	return $this->belongsToMany(Permission::class, 'roles_permissions');
    }

    public function permissions(){
        return $this->belongsToMany(Permission::class, 'roles_permissions');
    }
}
