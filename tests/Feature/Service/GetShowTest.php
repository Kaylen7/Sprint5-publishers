<?php
use App\Models\User;
use App\Models\Service;
use Database\Seeders\TestSeeder;

beforeEach(function(){
    $this->seed(TestSeeder::class);
    $this->regularUser = User::where('email', env('EMAIL'))->first();
    $this->adminUser = User::where('email', env('ADMIN_EMAIL'))->first();
});
describe('GET /services/{uuid}', function(){
    beforeEach(function(){
        $this->service = Service::factory()->create();
    });

    it('returns service details', function(){
        $response = $this->actingAs($this->regularUser)
        ->getJson('api/services/' . $this->service->uuid)
        ->assertStatus(200);

        expect($response->json())->toHaveKeys($this->serviceResource);
        expect($response->json())->not->toHaveKeys(['id', 'created_at', 'updated_at']);
    });
});