<?php
use App\Models\User;
use Database\Seeders\TestSeeder;

beforeEach(function(){
    $this->seed(TestSeeder::class);
    $this->regularUser = User::where('email', env('EMAIL'))->first();
    $this->adminUser = User::where('email', env('ADMIN_EMAIL'))->first();

});
describe('/user/{uuid} show', function(){
    test('non-auth can see their details', function(){

        $response = $this->actingAs($this->regularUser)
        ->getJson('api/users/' . $this->regularUser->uuid)
        ->assertJsonStructure($this->showUserResource)
        ->assertStatus(200);
    });

    test("non-auth can't see other people's details", function(){
        $user = User::factory()->create();
        $response = $this->actingAs($this->regularUser)
        ->getJson('api/users/' . $user->uuid)
        ->assertStatus(403);
    });

    test("admin can see other people's details", function(){
        $response = $this->actingAs($this->adminUser)
        ->getJson('api/users/' . $this->regularUser->uuid)
        ->assertStatus(200);
    });
});