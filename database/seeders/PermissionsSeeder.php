<?php

namespace Database\Seeders;

use App\Enums\Permissions;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionsSeeder extends Seeder
{
    public function run(): void
    {
        foreach (Permissions::getAllFunctionsControllerName() as $functionName) {
            foreach (Permissions::cases() as $permissionCase) {
                $permissionName = $permissionCase->$functionName();

                Permission::firstOrCreate(['name' => $permissionName]);
            }
        }
    }
}

