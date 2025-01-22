<?php
use App\Models\User;
use Database\Seeders\TestSeeder;

beforeEach(function(){
    $this->seed(TestSeeder::class);
    $this->regularUser = User::where('email', env('EMAIL'))->first();
    $this->adminUser = User::where('email', env('ADMIN_EMAIL'))->first();

});

describe('/user update', function() {

    test('non-auth can update themself', function() {

        $response = $this->actingAs($this->regularUser)
        ->putJson('api/users/' . $this->regularUser->uuid, [
            'name' => 'Something'
        ])
        ->assertStatus(200);
        expect($response['name'])->toBe('Something');
    });

    test('non-auth cannot update others', function(){
        $user = User::factory()->create();
        $response = $this->actingAs($this->regularUser)
        ->putJson('api/users/' . $user->uuid, [
            'name' => 'Something'
        ])
        ->assertStatus(403);
    });

    test('admin can update others', function(){
        $response = $this->actingAs($this->regularUser)
        ->putJson('api/users/' . $this->regularUser->uuid, [
            'name' => 'Something'
        ])
        ->assertStatus(200);
    });
    
    it('returns 204 on empty request', function(){
        $response =  $this->actingAs($this->regularUser)
        ->putJson('api/users/' . $this->regularUser->uuid)
        ->assertStatus(204);
    });

    it('prevents duplicated email', function(){
        $response =  $this->actingAs($this->regularUser)
        ->putJson('api/users/' . $this->regularUser->uuid, [
            'email' => env('ADMIN_EMAIL')
        ])
        ->assertStatus(422);
    });

    it('does not display id on response', function(){
        $response = $this->actingAs($this->regularUser)
        ->putJson('api/users/' . $this->regularUser->uuid, [
            'name' => 'Test Name'
        ])
        ->assertJsonStructure($this->showUserResource)
        ->assertStatus(200);
        
        expect($response->json())->not->toHaveKey('id');
    });
});