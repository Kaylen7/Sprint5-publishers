<?php
use App\Models\User;

beforeEach(function(){
    $this->seed(TestSeeder::class);
    $regularToken = $this->postJson('api/login', [
        'email' => env('EMAIL'),
        'password' => env('PASSWORD')
    ]);
    $adminToken = $this->postJson('api/login', [
        'email' => env('ADMIN_EMAIL'),
        'password' => env('ADMIN_PASSWORD')
    ]);
    Config::set('test.regular_token', $regularToken);
    Config::set('test.admin_token', $adminToken);
    $this->regularUser = User::where('email', env('EMAIL'))->first();
    $this->regularToken = config('test.regular_token')['access_token'];
    $this->adminToken = config('test.admin_token')['access_token'];
});

describe('User Resource', function(){
    describe('authentication', function(){
        test('endpoints require authentication', function() {
            
            //unauthenticated
            $this->getJson('api/users')
            ->assertStatus(401); 
            $this->getJson('api/users/' . $this->regularUser->id)
            ->assertStatus(401);
            $this->putJson('api/users/' . $this->regularUser->id)
            ->assertStatus(401);
            $this->deleteJson('api/users/' . $this->regularUser->id)
            ->assertStatus(401);
        });
    });

    describe('/user index', function(){
        beforeEach(function(){
            User::factory(5)->create();
        });
        test('admin user can view all users', function(){
            $response = $this->withHeader('Authorization', 'Bearer ' . $this->adminToken)
            ->getJson('api/users')
            ->assertStatus(200);

            $data = $response->json()['data'];
            expect(count($data))->toBe(7);
            expect($data[0])->toHaveKeys($this->userStructure);
        });

        test('non-admins can only view limited user data', function(){

            $response = $this->withHeader('Authorization', 'Bearer ' . $this->regularToken)
            ->getJson('api/users')
            ->assertStatus(200);

            $data = $response->json()['data'];
            expect(count($data))->toBe(7);
            expect($data[0])->toHaveKeys($this->regularUserResource);
        });

    });

    describe('/user/{id} show', function(){
        test('non-auth can see their details', function(){

            $response = $this->withHeader('Authorization', 'Bearer ' . $this->regularToken)
            ->getJson('api/users/' . $this->regularUser->id)
            ->assertJsonStructure($this->userStructure)
            ->assertStatus(200);
        });

        test("non-auth can't see other people's details", function(){
            $user = User::factory()->create();
            $response = $this->withHeader('Authorization', 'Bearer ' . $this->regularToken)
            ->getJson('api/users/' . $user->id)
            ->assertStatus(403);
        });

        test("admin can see other people's details", function(){
            $user = User::factory()->create();
            $response = $this->withHeader('Authorization', 'Bearer ' . $this->adminToken)
            ->getJson('api/users/' . $this->regularUser->id)
            ->assertStatus(200);
        });
    });

    describe('/user update', function() {

        test('non-auth can update themself', function() {

            $response = $this->withHeader('Authorization', 'Bearer ' . $this->regularToken)
            ->putJson('api/users/' . $this->regularUser->id, [
                'name' => 'Something'
            ])
            ->assertStatus(200);
            expect($response['name'])->toBe('Something');
        });

        test('non-auth cannot update others', function(){
            $user = User::factory()->create();
            $response = $this->withHeader('Authorization', 'Bearer ' . $this->regularToken)
            ->putJson('api/users/' . $user->id, [
                'name' => 'Something'
            ])
            ->assertStatus(403);
        });

        test('admin can update others', function(){
            $response = $this->withHeader('Authorization', 'Bearer ' . $this->regularToken)
            ->putJson('api/users/' . $this->regularUser->id, [
                'name' => 'Something'
            ])
            ->assertStatus(200);
        });
        
        it('returns 204 on empty request', function(){
            $response =  $this->withHeader('Authorization', 'Bearer ' . $this->regularToken)
            ->putJson('api/users/' . $this->regularUser->id)
            ->assertStatus(204);
        });

        it('prevents duplicated email', function(){
            $response =  $this->withHeader('Authorization', 'Bearer ' . $this->regularToken)
            ->putJson('api/users/' . $this->regularUser->id, [
                'email' => env('ADMIN_EMAIL')
            ])
            ->assertStatus(422);
        });
    });
    describe("/user destroy", function(){
        test("admin can destroy anyone without password", function(){
            $user = User::factory()->create();
            $response =  $this->withHeader('Authorization', 'Bearer ' . $this->adminToken)
            ->deleteJson('api/users/' . $user->id)
            ->assertStatus(200);

            expect($response["message"])->toContain("removed", "successfully");
            expect(User::find($user->id))->toBe(null);
        });

        test("regular user can destroy themself with password", function(){
            $response =  $this->withHeader('Authorization', 'Bearer ' . $this->regularToken)
            ->deleteJson('api/users/' . $this->regularUser->id, [
                'password' => 'password'
            ])
            ->assertStatus(200);
            expect($response["message"])->toContain("removed", "successfully");
            expect(User::find($this->regularUser->id))->toBe(null);
        });

        test("admin is indestructible", function(){
            $admin = User::where("email", env("ADMIN_EMAIL"))->first();
            $response =  $this->withHeader('Authorization', 'Bearer ' . $this->adminToken)
            ->deleteJson('api/users/' . $admin->id)
            ->assertStatus(403);
            expect($response["error"])->toContain("🧙");
            expect(User::find($admin->id))->toBeTruthy();
        });
    });

});