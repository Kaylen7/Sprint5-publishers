<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Project;
use App\Models\Service;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password'
        ]);

        Project::factory()->create();
        Project::factory(2)->done()->create();

        Service::factory()->create();
        Service::factory()->proofreading()->create();
        Service::factory()->translating()->create();
    }
}
