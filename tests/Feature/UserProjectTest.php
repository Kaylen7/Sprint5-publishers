<?php
use App\Models\User;
use App\Models\Project;
use Database\Seeders\TestSeeder;

beforeEach(function(){
    $this->seed(TestSeeder::class);
    $this->regularUser = User::where('email', env('EMAIL'))->first();
    $this->adminUser = User::where('email', env('ADMIN_EMAIL'))->first();

});

describe('User-Project relationships', function(){

    test('/users/{uuid}/projects shows user projects', function(){
        $project = Project::factory()->create([
            'owner_id' => $this->regularUser->id
        ]);

        expect(Project::all()->count())->toBe(1);

        $response = $this->actingAs($this->regularUser)
        ->getJson('api/users/' . $this->regularUser->uuid . '/projects')
        ->assertStatus(200);

        expect(count($response->json()))->toBe(1);

    });

    test('/users shows project count', function(){
        $response = $this->actingAs($this->regularUser)
        ->getJson('api/users')
        ->assertStatus(200);

        expect($response->json()[0])->toHaveKey('project_count');
    });

    test('/users/{uuid} shows project details', function(){
        $response = $this->actingAs($this->regularUser)
        ->getJson('api/users/' . $this->regularUser->uuid)
        ->assertStatus(200);

        expect($response->json())->toHaveKey('projects');
    });

    test('user project count updates after new project', function(){
        $user = User::factory()->create();
        $initialCount = $user->getProjectCount();
        expect($initialCount)->toBe(0);
        $projectOne = Project::factory()->create(['owner_id' => $user->id]);
        $updatedCount = $user->getProjectCount();
        expect($updatedCount)->toBe(1);
    });
    test('project entry gets removed if user is removed', function(){
        $user = User::factory()->create();
        $project = Project::factory()->create(['owner_id' => $user->id]);
        expect($project->id)->toBe(1);
        expect(Project::find($project->id))->not->toBeNull();

        $user->delete();

        expect(User::find($user->id))->toBeNull();
        expect(Project::find($project->id))->toBeNull();
    });
});
