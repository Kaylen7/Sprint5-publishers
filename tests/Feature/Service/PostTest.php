
<?php
use App\Models\User;
use App\Models\Service;
use Database\Seeders\TestSeeder;

beforeEach(function(){
    $this->seed(TestSeeder::class);
    $this->regularUser = User::where('email', env('EMAIL'))->first();
    $this->adminUser = User::where('email', env('ADMIN_EMAIL'))->first();
});

describe('POST /services', function(){
    it('throws error on missing fields', function(){
        $response = $this->actingAs($this->regularUser)
        ->postJson('api/services')
        ->assertStatus(422);
    });

    it('lets service creation', function(){
        $data = [
            'type' => 'proofreading',
            'languages' => ['es-ES', 'ca-ES']
        ];
        $response = $this->actingAs($this->regularUser)
        ->postJson('api/services', $data)
        ->assertStatus(201);

        expect($response->json())->toHaveKeys($this->serviceResource);
        expect($response->json())->not->toHaveKeys(['created_at', 'updated_at', 'id']);
    });

    it("lets admin create service for others", function(){
        $data = [
            'type' => 'proofreading',
            'languages' => ['es-ES', 'ca-ES'],
            'user_id' => $this->regularUser->id
        ];

        $response = $this->actingAs($this->adminUser)
        ->postJson('api/services', $data)
        ->assertStatus(201);

        $service = Service::where('uuid', $response->json()['uuid'])->first();
        expect($service->user_id)->toBe($this->regularUser->id);
    });

    it("lets admin create service for themself", function(){
        $data = [
            'type' => 'translating',
            'languages' => [['source' => 'es-ES', 'target' => 'ca-ES', 'bidirectional' => false]]
        ];

        $response = $this->actingAs($this->adminUser)
        ->postJson('api/services', $data)
        ->assertStatus(201);
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
