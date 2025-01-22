<?php
use App\Models\User;
use App\Models\Project;
use Database\Seeders\TestSeeder;

beforeEach(function(){
    $this->seed(TestSeeder::class);
    $this->regularUser = User::where('email', env('EMAIL'))->first();
    $this->adminUser = User::where('email', env('ADMIN_EMAIL'))->first();
});

describe('/projects/{uuid}', function(){

beforeEach(function(){
    $this->ownedProject = Project::factory()->create([
        'owner_id' => 1
    ]);
    $this->otherProject = Project::factory()->create([
        'owner_id' => 2
    ]);
});

    it('Shows owned project', function(){
        $response = $this->actingAs($this->regularUser)
        ->get('/api/projects/' . $this->ownedProject->uuid)
        ->assertStatus(200);

        expect($response->json())->toHaveKeys($this->projectResource);
        expect($response->json())->not->toHaveKeys(['id', 'created_at', 'updated_at']);
    });
    it('Shows other projects', function(){
        $response = $this->actingAs($this->regularUser)
        ->get('/api/projects/' . $this->otherProject->uuid)
        ->assertStatus(200);
    });
    it('Returns 404 on non-existing project', function(){
        $this->actingAs($this->regularUser)
        ->get('/api/projects/1')
        ->assertStatus(404);
    });
});