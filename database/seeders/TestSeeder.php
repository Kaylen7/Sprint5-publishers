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
            'email' => env('EMAIL'),
            'password' => Hash::make(env('PASSWORD'))
        ]);

        $admin = User::factory()->create([
            'email' => env('ADMIN_EMAIL'),
            'password' => Hash::make(env('ADMIN_PASSWORD'))
        ])->assignRole('admin');

        $client = Client::factory()->create([
            'id' => env('PASSWORD_CLIENT_ID'),
            'secret' => env('PASSWORD_CLIENT_SECRET'),
            'password_client' => true,
            'redirect' => 'http://localhost'
        ]);

    }
}
