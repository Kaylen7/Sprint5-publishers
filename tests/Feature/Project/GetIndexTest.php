<?php
use App\Models\User;
use App\Models\Project;
use Database\Seeders\TestSeeder;

beforeEach(function(){
    $this->seed(TestSeeder::class);
    $this->regularUser = User::where('email', env('EMAIL'))->first();
    $this->adminUser = User::where('email', env('ADMIN_EMAIL'))->first();
});
describe('/projects', function(){
    beforeEach(function(){
        Project::factory(1)->create();
        Project::factory(3)->done()->create();
    });

    describe('regular authorization', function(){

        it('/projects shows all projects with status done', function(){
            $response = $this->actingAs($this->regularUser)
            ->get('/api/projects?status=done')
            ->assertStatus(200);

            expect(count($response->json()))->toBe(3);
            expect(array_keys($response->json()[0]))->toBe($this->projectResource);
        });
    });

    describe('admin authorization', function(){
        it('/projects shows expanded info', function(){
            $response = $this->actingAs($this->adminUser)
            ->get('/api/projects')
            ->assertStatus(200);
            
            $data = $response->json()[0];
            expect($data)->toHaveKeys($this->projectResourceExpanded);
            expect($data)->not->toHaveKeys(['id']);
        });
    });  
});