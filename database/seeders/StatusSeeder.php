<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Seeder;

class StatusSeeder extends Seeder
{
    public function run(): void
    {
        Status::query()->insert([
            [
                'id' => 1,
                'name' => 'waiting',
            ],
            [
                'id' => 2,
                'name' => 'Detect',
            ],
            [
                'id'=>3,
                'name'=>'Execute'
            ],
            [
                'id'=>4,
                'name'=>'Done'
            ]
        ]);
    }
}
