<?php
use App\Models\User;
use Laravel\Passport\Client;
use Database\Seeders\TestSeeder;
use Illuminate\Support\Facades\DB; //for debugging
use Illuminate\Support\Facades\Hash; //for debugging
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function(){
    // \DB::purge(); 
    $this->seed(TestSeeder::class);
});

it('can generate a token for a user', function() {

    $testUser = User::where('email', 'test@example.com')->first();
    //dd(Hash::check('password', $testUser->password));

    $testClient = Client::where('user_id', $testUser->id)->first();
    //dd($testClient->secret);

    $response = $this->postJson('oauth/token', [
        'grant_type' => 'password',
        'client_id' => $testClient->id,
        'client_secret' => 'test-client-secret',
        'username' => $testUser->email,
        'password' => 'password',
        'scope' => '',
    ]);

    $response->assertStatus(200);
});
