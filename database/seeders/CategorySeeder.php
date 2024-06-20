<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
         Category::query()->insert([
             [
                 'id' => 1,
                 'name' => 'panel',
             ],
             [
                 'id' => 2,
                 'name' => 'battery',
             ],
             [
                 'id' => 3,
                 'name' => 'inverter',
             ]
         ]);
    }
}
