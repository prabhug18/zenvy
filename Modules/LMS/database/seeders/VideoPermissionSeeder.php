<?php

namespace Modules\LMS\Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class VideoPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            ['name' => 'menu.homepage-video', 'guard_name' => 'admin', 'module' => 'homepage-video'],
            ['name' => 'add.homepage-video', 'guard_name' => 'admin', 'module' => 'homepage-video'],
            ['name' => 'edit.homepage-video', 'guard_name' => 'admin', 'module' => 'homepage-video'],
            ['name' => 'delete.homepage-video', 'guard_name' => 'admin', 'module' => 'homepage-video'],
            ['name' => 'status.homepage-video', 'guard_name' => 'admin', 'module' => 'homepage-video'],
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission['name'], 'guard_name' => $permission['guard_name']], $permission);
        }

        $adminRole = Role::where('name', 'admin')->first();
        if ($adminRole) {
            $adminRole->givePermissionTo(collect($permissions)->pluck('name')->toArray());
        }
    }
}
