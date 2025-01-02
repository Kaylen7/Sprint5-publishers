<?php

namespace Database\Seeders;

use App\Models\User;
use Laravel\Passport\Client;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password')
        ]);

        $client = Client::factory()->create([
            'user_id' => $user->id,
            'secret' => 'test-client-secret',
            'password_client' => true,
            'redirect' => 'http://localhost'
        ]);
    }
}
