<?php
use App\Models\User;
use App\Models\Service;
use Database\Seeders\TestSeeder;

beforeEach(function(){
    $this->seed(TestSeeder::class);
    $this->regularUser = User::where('email', env('EMAIL'))->first();
    $this->adminUser = User::where('email', env('ADMIN_EMAIL'))->first();
});

describe('DESTROY /services/{uuid}', function(){
    beforeEach(function(){
        $this->service = Service::factory()->create([
            'user_id' => $this->regularUser->id
        ]);
    });
    it('lets user delete their service', function(){
        $response = $this->actingAs($this->regularUser)
        ->deleteJson('api/services/' . $this->service->uuid)
        ->assertStatus(200);
    });
    it('prevents user from deleting other services', function(){
        $service = Service::factory()->create([
            'user_id' => $this->adminUser->id
        ]);
        $this->actingAs($this->regularUser)
        ->deleteJson('api/services/' . $service->uuid)
        ->assertStatus(403);
    });
    it('lets admin destroy other services', function(){
        $this->actingAs($this->adminUser)
        ->deleteJson('api/services/' . $this->service->uuid)
        ->assertStatus(200);
    });
});