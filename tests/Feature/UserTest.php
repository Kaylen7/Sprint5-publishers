<?php
use App\Models\User;

beforeEach(function(){
    $this->seed(TestSeeder::class);
    $this->regularUser = User::where('email', env('EMAIL'))->first();
    $this->adminUser = User::where('email', env('ADMIN_EMAIL'))->first();

});

describe('User Resource', function(){
    describe('authentication', function(){
        dataset('user_endpoints', function(){
            $endpoint = 'api/users';
            return [
                ['getJson', $endpoint],
                ['getJson', $endpoint . '/1'],
                ['putJson', $endpoint . '/1'],
                ['deleteJson', $endpoint . '/1']
            ];
        });
        test('endpoints require authentication', function($method, $endpoint) {
            $this->$method($endpoint)->assertStatus(401);
        })->with('user_endpoints');
    });

    describe('/user index', function(){
        test('admin user can view all users', function(){
            $response = $this->actingAs($this->adminUser)
            ->getJson('api/users')
            ->assertStatus(200);

            $data = $response->json()['data'];
            expect(count($data))->toBe(2);
            expect(array_keys($data[0]))->toBe($this->userStructure);
        });

        test('non-admins can only view limited user data', function(){

            $response = $this->actingAs($this->regularUser)
            ->getJson('api/users')
            ->assertStatus(200);

            $data = $response->json()['data'];
            expect(count($data))->toBe(2);
            expect(array_keys($data[0]))->toBe($this->regularUserResource);
        });

    });

    describe('/user/{id} show', function(){
        test('non-auth can see their details', function(){

            $response = $this->actingAs($this->regularUser)
            ->getJson('api/users/' . $this->regularUser->uuid)
            ->assertJsonStructure($this->userStructure)
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

});