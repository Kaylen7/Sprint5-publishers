<?php
use App\Models\User;
use Database\Seeders\TestSeeder;

beforeEach(function(){
    $this->seed(TestSeeder::class);
    $this->regularUser = User::where('email', env('EMAIL'))->first();
    $this->adminUser = User::where('email', env('ADMIN_EMAIL'))->first();

});

describe('/user index', function(){
    test('admin user can view all users', function(){
        $response = $this->actingAs($this->adminUser)
        ->getJson('api/users')
        ->assertStatus(200);

        $data = $response->json();
        expect(count($data))->toBe(2);
        expect(array_keys($data[0]))->toBe($this->adminUserResource);
    });

    test('non-admins can only view limited user data', function(){

        $response = $this->actingAs($this->regularUser)
        ->getJson('api/users')
        ->assertStatus(200);

        $data = $response->json();
        expect(count($data))->toBe(2);
        expect(array_keys($data[0]))->toBe($this->regularUserResource);
    });

});