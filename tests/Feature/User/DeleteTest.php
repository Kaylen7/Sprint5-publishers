<?php
use App\Models\User;
use Database\Seeders\TestSeeder;

beforeEach(function(){
    $this->seed(TestSeeder::class);
    $this->regularUser = User::where('email', env('EMAIL'))->first();
    $this->adminUser = User::where('email', env('ADMIN_EMAIL'))->first();

});

describe("/user destroy", function(){
    test("admin can destroy anyone without password", function(){
        $user = User::factory()->create();
        $response =  $this->actingAs($this->adminUser)
        ->deleteJson('api/users/' . $user->uuid)
        ->assertStatus(200);

        expect($response["message"])->toContain("removed", "successfully");
        expect(User::find($user->id))->toBe(null);
    });

    test("regular user can destroy themself with password", function(){
        $response =  $this->actingAs($this->regularUser)
        ->deleteJson('api/users/' . $this->regularUser->uuid, [
            'password' => 'password'
        ])
        ->assertStatus(200);
        expect($response["message"])->toContain("removed", "successfully");
        expect(User::find($this->regularUser->id))->toBe(null);
    });

    test("admin is indestructible", function(){
        $admin = User::where("email", env("ADMIN_EMAIL"))->first();
        $response =  $this->actingAs($this->adminUser)
        ->deleteJson('api/users/' . $admin->uuid)
        ->assertStatus(403);
        expect($response["error"])->toContain("🧙");
        expect(User::find($admin->id))->toBeTruthy();
    });
});