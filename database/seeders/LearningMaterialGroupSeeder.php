<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LearningMaterialGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
            DB::table('learning_material_groups')->insert([
            [
                'name' => 'Vessel Lights and Day Shapes ',
            ],
            [
                'name' => 'Sound Signals',
            ]
        ]);
    }
}
