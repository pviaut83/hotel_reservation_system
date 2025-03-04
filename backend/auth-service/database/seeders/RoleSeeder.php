<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder {
    public function run() {
        $admin = Role::create(['name' => 'admin']);
        $user = Role::create(['name' => 'user']);
        $receptionist = Role::create(['name' => 'receptionist']);

        Permission::create(['name' => 'manage_users']);
        Permission::create(['name' => 'manage_reservations']);
        Permission::create(['name' => 'manage_payments']);
        Permission::create(['name' => 'create_reservations']);
        Permission::create(['name' => 'view_reservations']);

        $admin->givePermissionTo(['manage_users', 'manage_reservations', 'manage_payments']);
        $receptionist->givePermissionTo(['manage_reservations']);
        $user->givePermissionTo(['create_reservations', 'view_reservations']);
    }
}
?>