<?php
use App\Models\User;
use Laravel\Passport\Client;
use Laravel\Passport\Passport;
use Database\Seeders\TestSeeder;
use Illuminate\Support\Facades\Http;

beforeEach(function(){
    $this->seed(TestSeeder::class);
    Config::set('passport.password.id', '1');
    Config::set('passport.password.secret', 'test-password-client');
});
describe('Authentication', function(){

    test('oauth/token works', function() {

        $response = $this->postJson('oauth/token', [
            'grant_type' => 'password',
            'client_id' => env('PASSWORD_CLIENT_ID'),
            'client_secret' => env('PASSWORD_CLIENT_SECRET'),
            'username' => env('EMAIL'),
            'password' => env('PASSWORD'),
            'scope' => '',
        ]);
    
        $response->assertStatus(200)
        ->assertJsonStructure($this->tokenStructure);
        
    });

    test('/register works', function(){
        $response = $this->postJson('api/register', [
            'name' => 'Test User',
            'email' => 'random@email.com',
            'password' => 'password1234'
        ]);

        $response->assertStatus(200)
        ->assertJsonStructure([
            'token' => $this->tokenStructure,
            'user' => $this->userStructure
        ]);
    });

    test('/login works', function(){
        $response = $this->postJson('api/login', [
            'email' => env('EMAIL'),
            'password' => env('PASSWORD')
        ]);

        $response->assertStatus(200)
        ->assertJsonStructure($this->tokenStructure);
    });

    test('/logout works', function(){
        $token = $this->postJson('api/login', [
            'email' => env('EMAIL'),
            'password' => env('PASSWORD')
        ]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $token["access_token"])
        ->postJson('api/logout');
        $response->assertStatus(200);
    });

});

