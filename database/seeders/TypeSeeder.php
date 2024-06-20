<?php

namespace Database\Seeders;

use App\Models\Type;
use Illuminate\Database\Seeder;

class TypeSeeder extends Seeder
{
    public function run(): void
    {
        Type::query()->insert([
            [
                'id' => 1,
                'name' => 'Maintenance',
            ],
            [
                'id' => 2,
                'name' => 'Composition',
            ]
        ]);
    }
}
