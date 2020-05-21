<?php

use Illuminate\Database\Migrations\Migration;
use App\Models\User;
use Illuminate\Support\Facades\Config;
use Spatie\Permission\Models\Role;

class CreateAdminUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $admin_user = User::create(['name' => 'admin', 'email' => Config::get('app.admin_email'), 'password' => bcrypt(Config::get('app.admin_password'))]);
        $admin_role = Role::findByName('admin');
        $admin_user->assignRole($admin_role);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        User::find(['name' => 'admin'])->delete();
    }
}
