<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Create Default Project
        $defaultProjectId = DB::table('projects')->insertGetId([
            'name' => 'Default Project',
            'description' => 'This project contains all existing tasks.',
            'created_by' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Assign all existing tasks to this default project
        DB::table('tasks')->update(['project_id' => $defaultProjectId]);
    }
}
