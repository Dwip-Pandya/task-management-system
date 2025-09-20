<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tags')->insert([
            ['name' => 'meeting'],
            ['name' => 'bug'],
            ['name' => 'feature'],
            ['name' => 'maintenance'],
            ['name' => 'design'],
            ['name' => 'testing'],
            ['name' => 'documentation'],
            ['name' => 'research'],
            ['name' => 'deployment'],
            ['name' => 'support'],
            ['name' => 'review'],
            ['name' => 'optimization'],
            ['name' => 'training'],
            ['name' => 'planning'],
            ['name' => 'other'],
        ]);
    }
}
