<?php
use Laravel\Passport\Client;
use Database\Seeders\TestSeeder;
use App\Models\User;

describe('Authentication', function(){
    it('has oauth/token endpoint available', function () {
        $response = $this->postJson('oauth/token', []);
        $response->assertStatus(400);
    });

    it('can generate a token for a user', function() {
    
        $this->seed(TestSeeder::class);

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

});

