<?php
use App\Models\User;
use App\Models\Project;
use Database\Seeders\TestSeeder;

beforeEach(function(){
    $this->seed(TestSeeder::class);
    $this->regularUser = User::where('email', env('EMAIL'))->first();
    $this->adminUser = User::where('email', env('ADMIN_EMAIL'))->first();
});
describe('PUT /projects/{id}', function(){
    beforeEach(function(){
        $this->project = Project::factory()->createOne([
            'name' => 'Test',
            'description' => 'Some description',
            'owner_id' => $this->regularUser->id
        ]);
    });
    it('admin updates any project', function(){
        $response = $this->actingAs($this->adminUser)
        ->putJson('api/projects/' . $this->project->uuid, [
            'name' => 'Something else'
        ])
        ->assertStatus(200);

        $updatedProject = Project::find($this->project->id);
        expect($updatedProject->name)->toBe('Something else');
    });

    it('can be updated by owner. Not by others', function(){
        $user = User::factory()->create();
        $data = [
            'name' => 'Hola',
            'description' => 'Qué tal'
        ];

        $response = $this->actingAs($this->regularUser)
        ->putJson('api/projects/' . $this->project->uuid, $data)
        ->assertStatus(200);

        $updatedProject = Project::find($this->project->id);
        expect($updatedProject->name)->toBe('Hola');
        expect($updatedProject->description)->toBe('Qué tal');

        $response = $this->actingAs($user)
        ->putJson('api/projects/' . $this->project->uuid, $data)
        ->assertStatus(403);
    });

    it('returns 204 on empty request', function(){
        $response = $this->actingAs($this->regularUser)
        ->putJson('api/projects/' . $this->project->uuid, [])
        ->assertStatus(204);
    });

    it('prevents modifying owner_id to regular user', function(){
        $user = User::factory()->create();
        $response = $this->actingAs($this->regularUser)
        ->putJson('api/projects/' . $this->project->uuid, [
            'owner_id' => $user->id
        ]);
        $project = Project::find($this->project->id);
        expect($project->owner_id)->not->toBe($user->id);
    });

    it('allows changing owner_id to admin', function(){
        $user = User::factory()->create();
        $project = Project::factory()->create(['owner_id' => $user->id]);
        $response = $this->actingAs($this->adminUser)
        ->putJson('api/projects/' . $project->uuid, [
            'owner_id' => $this->regularUser->id
        ])
        ->assertStatus(200);
        
        $project = Project::find($project->id);
        expect($project->owner_id)->toBe($this->regularUser->id);
    });

    it('does not show id on response', function(){
        $project = Project::factory()->create(['owner_id' => $this->regularUser->id]);
        $response = $this->actingAs($this->regularUser)
        ->putJson('api/projects/' . $project->uuid, [
            'name' => 'Testing new name'
        ])
        ->assertStatus(200);
        expect($response->json())->toHaveKeys($this->projectResource);
        expect($response->json())->not->toHaveKeys(['id']);
    });

});