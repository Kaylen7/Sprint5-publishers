<?php
use App\Models\User;
use App\Models\Project;
use Database\Seeders\TestSeeder;

beforeEach(function(){
    $this->seed(TestSeeder::class);
    $this->regularUser = User::where('email', env('EMAIL'))->first();
    $this->adminUser = User::where('email', env('ADMIN_EMAIL'))->first();
});

describe("DESTROY /projects/{uuid}", function(){
    beforeEach(function(){
        $user = User::factory()->create();
        $this->project = Project::factory()->create([
            'owner_id' => $user->id
        ]);
        $this->projectId = $this->project->id;
    });
    it('lets admin remove any project', function(){
        $response = $this->actingAs($this->adminUser)
        ->deleteJson('api/projects/' . $this->project->uuid)
        ->assertStatus(200);

        expect($response["message"])->toContain("removed", "successfully");

        $project = Project::find($this->projectId);
        expect($project)->toBe(null);
    });

    it('prevents user from removing other projects', function(){
        $response = $this->actingAs($this->regularUser)
        ->deleteJson('api/projects/' . $this->project->uuid)
        ->assertStatus(403);

        $project = Project::find($this->projectId);
        expect($project)->not->toBeEmpty();
    });
});