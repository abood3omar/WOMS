<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class RBACSeeder extends Seeder
{
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('rolesrights')->truncate();
        DB::table('entity_actions')->truncate();
        DB::table('entities')->truncate();
        DB::table('modules')->truncate();
        DB::table('actions')->truncate();
        DB::table('roles')->truncate();
        User::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');


        $systemStructure = [
            'System' => [
                'dashboard'   => ['view'],
                'work_orders' => ['view', 'create', 'edit', 'delete', 'change_status', 'assign'],
                'logs'        => ['view'],
            ],
            'Security' => [
                'system_modules' => ['view', 'create', 'edit', 'delete'],
                'role_rights'    => ['view', 'create', 'edit', 'delete'],
                'users'          => ['view', 'create', 'edit', 'delete'],
            ],
            'Operations' => [
                'my_orders' => ['view'],
            ]
        ];

        $allActions = ['view', 'create', 'edit', 'delete', 'change_status', 'assign'];
        $actionIds = [];
        foreach ($allActions as $actionName) {
            $actionIds[$actionName] = DB::table('actions')->insertGetId([
                'ActionName' => $actionName,
                'created_at' => now(), 
                'updated_at' => now()
            ]);
        }


        $adminRights = [];    
        $managerRights = [];  
        $operatorRights = []; 

        foreach ($systemStructure as $moduleName => $entities) {
            $moduleId = DB::table('modules')->insertGetId([
                'ModuleName' => $moduleName,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            foreach ($entities as $entityName => $actions) {
                $entityId = DB::table('entities')->insertGetId([
                    'EntityName' => $entityName,
                    'ModuleID'   => $moduleId,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

                foreach ($actions as $action) {
                    $actId = $actionIds[$action];

                    DB::table('entity_actions')->insert([
                        'EntityID' => $entityId,
                        'ActionID' => $actId,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);

                    $adminRights[] = ['entity_id' => $entityId, 'action_id' => $actId];

                    if ($moduleName !== 'Security') {
                        $managerRights[] = ['entity_id' => $entityId, 'action_id' => $actId];
                    }

                    if ($entityName === 'my_orders') {
                        $operatorRights[] = ['entity_id' => $entityId, 'action_id' => $actId];
                    }
                }
            }
        }

        $adminRoleId = DB::table('roles')->insertGetId(['RoleName' => 'Admin', 'created_at' => now(), 'updated_at' => now()]);
        $managerRoleId = DB::table('roles')->insertGetId(['RoleName' => 'Manager', 'created_at' => now(), 'updated_at' => now()]);
        $operatorRoleId = DB::table('roles')->insertGetId(['RoleName' => 'Operator', 'created_at' => now(), 'updated_at' => now()]);

        foreach ($adminRights as $right) {
            DB::table('rolesrights')->insert(array_merge($right, ['role_id' => $adminRoleId]));
        }

        foreach ($managerRights as $right) {
            DB::table('rolesrights')->insert(array_merge($right, ['role_id' => $managerRoleId]));
        }

        foreach ($operatorRights as $right) {
            DB::table('rolesrights')->insert(array_merge($right, ['role_id' => $operatorRoleId]));
        }
        
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@shabakat.com',
            'password' => Hash::make('password'),
            'RoleID' => $adminRoleId,
        ]);

        User::create([
            'name' => 'Manager User',
            'email' => 'manager@shabakat.com',
            'password' => Hash::make('password'),
            'RoleID' => $managerRoleId,
        ]);

        User::create([
            'name' => 'Operator User',
            'email' => 'operator@shabakat.com',
            'password' => Hash::make('password'),
            'RoleID' => $operatorRoleId,
        ]);
    }
}