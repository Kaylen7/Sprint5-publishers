<?php
use App\Models\User;
use App\Models\Service;
use Database\Seeders\TestSeeder;

beforeEach(function(){
    $this->seed(TestSeeder::class);
    $this->regularUser = User::where('email', env('EMAIL'))->first();
    $this->adminUser = User::where('email', env('ADMIN_EMAIL'))->first();
});

describe('GET /services', function(){
    beforeEach(function(){
        Service::factory(3)->translating()->create();
    });
    it('returns list of available services', function(){
        $response = $this->actingAs($this->regularUser)
        ->getJson('api/services')
        ->assertStatus(200);
        $data = $response->json()["data"];
        expect(array_keys($data[0]))->toBe($this->serviceResource);

        $languages = $response->json()["data"][0]["languages"];
        expect($languages)->toBeArray();
    });
});