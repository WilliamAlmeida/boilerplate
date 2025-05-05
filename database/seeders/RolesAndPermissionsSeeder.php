<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::query()->delete();
        Permission::query()->delete();
        DB::table('role_has_permissions')->delete();
        DB::table('model_has_roles')->delete();
        DB::table('model_has_permissions')->delete();

        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $arrayOfPermissionsNames = [
            'usuarios', 'roles', 'permissions', 'clientes'
        ];

        $crud = collect(['view', 'viewAny', 'create', 'edit', 'delete', 'forceDelete']);

        $permissions = collect($arrayOfPermissionsNames)->map(function ($permission) use ($crud) {
            $items = $crud->map(function ($item) use ($permission) { 
                return ['name' => "{$permission}.{$item}", 'guard_name' => 'web'];
            });
            return $items->all();
        })->collapse();

        Permission::insert($permissions->toArray());
        unset($crud);

        $arrayOfPermissionsNames = [
            'usuarios.edit_permissions',
        ];
        $permissions = collect($arrayOfPermissionsNames)->map(function ($permission) {
            return ['name' => $permission, 'guard_name' => 'web'];
        });

        Permission::insert($permissions->toArray());

        unset($arrayOfPermissionsNames, $permissions);

        $arrayOfRolesNames = ['super admin', 'admin'];
        $roles = collect($arrayOfRolesNames)->map(function ($role) {
            return ['name' => $role, 'guard_name' => 'web'];
        });

        Role::insert($roles->toArray());
        unset($arrayOfRolesNames, $roles);

        $role = Role::findByName('admin');
        $role->givePermissionTo(Permission::all());

        $usuario = User::whereType(User::ADMIN)->firstOrFail();
        $usuario->syncRoles($role);
    }
}
