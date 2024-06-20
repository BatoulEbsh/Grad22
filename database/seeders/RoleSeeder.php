<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        Role::query()->insert(
            [
                [
                    'id' => 1,
                    'name' => "Admin",
                ],
                [
                    'id' => 2,
                    'name' => "Team",
                ],
                [
                    'id' => 3,
                    'name' => "User"
                ]
            ]
        );
    }
}
