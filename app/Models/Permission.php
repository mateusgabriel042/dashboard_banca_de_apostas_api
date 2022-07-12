<?php

namespace App\Models;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Role;

class Permission extends Model
{
    use HasFactory, Uuid;

    protected $table = 'permissions';
    
    public $incrementing = false;

    protected $keyType = 'uuid';

    protected $fillable = [
        'id',
        'name',
        'slug',
    ];

    public function roles(){
    	return $this->belongsToMany(Role::class, 'roles_permissions');
    }
}
