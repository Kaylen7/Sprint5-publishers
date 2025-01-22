<?php
use App\Models\User;
use App\Models\Project;
use Database\Seeders\TestSeeder;

beforeEach(function(){
    $this->seed(TestSeeder::class);
    $this->regularUser = User::where('email', env('EMAIL'))->first();
    $this->adminUser = User::where('email', env('ADMIN_EMAIL'))->first();
});

describe('POST /projects', function(){
    it('creates project', function(){
        $tomorrow  = now()->addDays(1)->format("Y-m-d");
        $response = $this->actingAs($this->regularUser)
            ->postJson('/api/projects/', [
                'name' => 'Test',
                'description' => 'This is a test',
                'num_chars' => 100,
                'start_date' => $tomorrow
            ])
            ->assertStatus(201);

        $project = Project::where('uuid', $response->json()['uuid']);
        expect($project)->not->toBeNull();
    });
    it('returns 422 when validation fails', function(){
        $response = $this->actingAs($this->regularUser)
        ->postJson('/api/projects/', [
                'name' => 'Test',
                'description' => 'This is a test',
                'num_chars' => 100
            ])
            ->assertStatus(422);
    });

    it('returns 422 when creating duplicate projects', function(){
        $projectData = [
            'name' => 'Test',
            'description' => 'Some description',
            'num_chars' => 100,
            'start_date' => now()->addDays(1)->format('Y-m-d')
        ];

        $response = $this->actingAs($this->regularUser)
        ->postJson('api/projects', $projectData)->assertStatus(201);

        $response = $this->actingAs($this->regularUser)
        ->postJson('api/projects', $projectData)
        ->assertStatus(422);

        expect(Project::count())->toBe(1);
    });

    test('same project data can be used by different users', function(){
        $projectData = [
            'name' => 'Test',
            'description' => 'Some description',
            'num_chars' => 100,
            'start_date' => now()->addDays(1)->format('Y-m-d')
        ];

        $response = $this->actingAs($this->regularUser)
        ->postJson('api/projects', $projectData)->assertStatus(201);

        $response = $this->actingAs($this->adminUser)
        ->postJson('api/projects', $projectData)
        ->assertStatus(201);

        expect(Project::count())->toBe(2);
    });
});