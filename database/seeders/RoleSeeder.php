<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;
use Illuminate\Support\Facades\Hash;
use App\Services\UserService;
use App\Services\RoleService;

class RoleSeeder extends Seeder {
    public function run() {
        $user1 = UserService::createUserIfNotExist([
            'first_name' => 'Admin',
            'last_name' => 'Da Silva',
            'email' => 'admin@bancodeapostas.com',
            'birth_date' => '1999-02-04',
            'cpf' => '000.000.000-00',
            'mobile_phone' => '(00) 99999 - 9999',
            'userName' => 'admin.bancodeaposta',
            'password' => 'b@mço0fb3T$#',
        ]);
    	
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

            12 => ['name' => 'country-view', 'slug' => 'country-view'],
            13 => ['name' => 'country-create', 'slug' => 'country-create'],
            14 => ['name' => 'country-update', 'slug' => 'country-update'],
            15 => ['name' => 'country-delete', 'slug' => 'country-delete'],

            16 => ['name' => 'league-view', 'slug' => 'league-view'],
            17 => ['name' => 'league-create', 'slug' => 'league-create'],
            18 => ['name' => 'league-update', 'slug' => 'league-update'],
            19 => ['name' => 'league-delete', 'slug' => 'league-delete'],

            20 => ['name' => 'deposit-view', 'slug' => 'deposit-view'],
            21 => ['name' => 'deposit-create', 'slug' => 'deposit-create'],
            22 => ['name' => 'deposit-update', 'slug' => 'deposit-update'],
            23 => ['name' => 'deposit-delete', 'slug' => 'deposit-delete'],

            24 => ['name' => 'bet-purchase-view', 'slug' => 'bet-purchase-view'],
            25 => ['name' => 'bet-purchase-create', 'slug' => 'bet-purchase-create'],
            26 => ['name' => 'bet-purchase-update', 'slug' => 'bet-purchase-update'],
            27 => ['name' => 'bet-purchase-delete', 'slug' => 'bet-purchase-delete'],

            28 => ['name' => 'sport-view', 'slug' => 'sport-view'],
            29 => ['name' => 'sport-create', 'slug' => 'sport-create'],
            30 => ['name' => 'sport-update', 'slug' => 'sport-update'],
            31 => ['name' => 'sport-delete', 'slug' => 'sport-delete'],

            32 => ['name' => 'odd-view', 'slug' => 'odd-view'],
            33 => ['name' => 'odd-create', 'slug' => 'odd-create'],
            34 => ['name' => 'odd-update', 'slug' => 'odd-update'],
            35 => ['name' => 'odd-delete', 'slug' => 'odd-delete'],

            

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
