<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class CreateRolesAndPermissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $admin_role = Role::create(['name' => 'admin', 'guard_name' => 'api']);
        $view_all_users_permission = Permission::create(['name' => 'view_all_users', 'guard_name' => 'api']);
        $admin_role->givePermissionTo($view_all_users_permission);

        $user_role = Role::create(['name' => 'user', 'guard_name' => 'api']);
        $view_own_user_permission = Permission::create(['name' => 'view_own_user', 'guard_name' => 'api']);
        $user_role->givePermissionTo($view_own_user_permission);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Role::findByName('admin')->delete();
        Role::findByName('user')->delete();
        Permission::findByName("view_all_users")->delete();
        Permission::findByName("view_own_user")->delete();
    }
}
