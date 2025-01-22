<?php
use App\Models\User;
use App\Models\Service;
use Database\Seeders\TestSeeder;

beforeEach(function(){
    $this->seed(TestSeeder::class);
    $this->regularUser = User::where('email', env('EMAIL'))->first();
    $this->adminUser = User::where('email', env('ADMIN_EMAIL'))->first();
});

describe('PUT /services/{uuid}', function(){
    beforeEach(function(){
        $this->service = Service::factory()->create([
            'user_id' => $this->regularUser->id
        ]);
    });

    it("lets user update a service", function(){
        $response = $this->actingAs($this->regularUser)
        ->putJson('api/services/' . $this->service->uuid, [
            'name' => 'new name'
        ])
        ->assertStatus(200);

        expect($response->json()['name'])->toBe('new name');
    });

    it("returns 204 when no content is sent", function(){
        $this->actingAs($this->regularUser)
        ->putJson('api/services/' . $this->service->uuid)
        ->assertStatus(204);
    });

    it('does not allow user to update other user service', function(){
        $user = User::factory()->create();
        $service = Service::factory()->create([
            'name' => 'Test',
            'user_id' => $user->id
        ]);
        $response = $this->actingAs($this->regularUser)
        ->putJson('api/services/' . $service->uuid, [
            'name' => 'New name'
        ])
        ->assertStatus(403);
        expect(Service::find($service->id)["name"])->toBe("Test");
    });

    it('lets admin change other user service', function(){
        $user = User::factory()->create();
        $service = Service::factory()->create([
            'name' => 'Test',
            'user_id' => $user->id
        ]);
        $response = $this->actingAs($this->adminUser)
        ->putJson('api/services/' . $service->uuid, [
            'name' => 'New name'
        ])
        ->assertStatus(200);
        expect(Service::find($service->id)["name"])->toBe("New name");
    });

    it('does not allow regular user to change user_id', function(){
        $response = $this->actingAs($this->regularUser)
        ->putJson('api/services/' . $this->service->uuid, [
            'user_id' => $this->adminUser->id
        ])
        ->assertStatus(403);
    });

    it('lets admin change user_id', function(){
        $response = $this->actingAs($this->adminUser)
        ->putJson('api/services/' . $this->service->uuid, [
            'user_id' => $this->adminUser->id
        ])
        ->assertStatus(200);
    });

    test("user can only have one service of each type", function(){
        $user = User::factory()->create();
        $service = Service::factory()->proofreading()->create(['user_id' => $user->id]);
        $response = $this->actingAs($user)
        ->postJson('api/services', [
            "type" => "proofreading",
            "languages" => ["es-ES"]
        ])
        ->assertStatus(422);
    });
});