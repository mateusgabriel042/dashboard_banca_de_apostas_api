<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;
use Illuminate\Support\Facades\Hash;
use App\Services\UserService;
use App\Services\RoleService;

class RoleSeeder extends Seeder {
    public function run() {
        $user1 = UserService::createUserIfNotExist('Administrador', 'admin@bancodeapostas.com', 'b@mço0fb3T$#');
    	
        $role1 = RoleService::createRoleIfNotExist('admin', 'admin');
        $role2 = RoleService::createRoleIfNotExist('manager', 'manager');

        $permissions = [
        	0 => ['name' => 'dashboard-view', 'slug' => 'dashboard-view'],
        	1 => ['name' => 'dashboard-create', 'slug' => 'dashboard-create'],
        	2 => ['name' => 'dashboard-update', 'slug' => 'dashboard-update'],
        	3 => ['name' => 'dashboard-delete', 'slug' => 'dashboard-delete'],

        	4 => ['name' => 'user-view', 'slug' => 'user-view'],
        	5 => ['name' => 'user-create', 'slug' => 'user-create'],
        	6 => ['name' => 'user-update', 'slug' => 'user-update'],
        	7 => ['name' => 'user-delete', 'slug' => 'user-delete'],

        	8 => ['name' => 'acl-view', 'slug' => 'acl-view'],
        	9 => ['name' => 'acl-create', 'slug' => 'acl-create'],
        	10 => ['name' => 'acl-update', 'slug' => 'acl-update'],
        	11 => ['name' => 'acl-delete', 'slug' => 'acl-delete'],

        ];

        foreach ($permissions as $key => $item) {
            //verificando se a permissao ja esta cadastrada
            $permission = Permission::where('name', '=', $item['name'])->first();
            if($permission == null){
                $permission = Permission::create($item);
                $role1->permissions()->attach($permission->id);//se a permissao for cadastrada sera realizado o relacionamento com o admin
            }
        }
        
        if (!UserService::relationUserRoleExist($user1['id'], $role1['id']))
            $user1->roles()->attach($role1);
    }
}
