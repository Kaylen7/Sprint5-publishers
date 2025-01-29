<?php
use App\Models\User;
use App\Models\Project;
use App\Models\Service;

describe('User Service Management', function(){
    beforeEach(function(){
        $this->user = User::factory()->create();
    });
    test('GET /users shows service types', function(){
        
        $service = Service::factory()->proofreading()->create([
            'user_id' => $this->user->id
        ]);

        $response = $this->actingAs($this->user)
        ->getJson('api/users')
        ->assertStatus(200);
        
        $data = $response->json()[1];
        expect($data)->toHaveKey('services');
        expect($data["services"])->toHaveKey('proofreading');
    });

    test('GET /users shows only available services', function(){
        $user = User::factory()->create();
        $service = Service::factory()->proofreading()->create(['available' => false, 'user_id' => $this->user->id]);
        $service = Service::factory()->translating()->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->user)
        ->getJson('api/users')
        ->assertStatus(200);

        $data = $response->json()[0];
        expect($data)->toHaveKey('services');
        expect($data["services"])->not->toHaveKey('proofreading');
    });

    test('GET /users/{uuid} shows detailed services', function(){
        $service = Service::factory()->create(['user_id' => $this->user->id]);
        $response = $this->actingAs($this->user)
        ->getJson('api/users/' . $this->user->uuid)
        ->assertStatus(200);

        $data = $response->json();
        expect($data)->toHaveKey('services');
        expect($data['services'][0])->toHaveKeys($this->serviceResource);
        expect($data['services'][0])->not->toHaveKey('id');
    });
});
