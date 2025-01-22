<?php

use App\Models\User;
use App\Models\Project;
use Database\Seeders\TestSeeder;

describe('Project Management', function(){

    describe('authentication', function(){
        dataset('project_endpoints', function(){
            $endpoint = 'api/projects';
            return [
                ['getJson', $endpoint],
                ['getJson', $endpoint . '/1'],
                ['postJson', $endpoint],
                ['putJson', $endpoint . '/1'],
                ['deleteJson', $endpoint . '/1']
            ];
        });
        test('endpoints require authentication', function($method, $endpoint) {
            $this->$method($endpoint)->assertStatus(401);
        })->with('project_endpoints');
    });
    
    beforeEach(function(){
        $this->seed(TestSeeder::class);
        $this->regularUser = User::where('email', env('EMAIL'))->first();
        $this->adminUser = User::where('email', env('ADMIN_EMAIL'))->first();
    });

    /**
     * GET api/projects
     */
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

    /**
     * GET api/projects/{uuid}
     */
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

    /**
     * POST /api/projects/
     */
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

    /**
     * PUT api/projects/{uuid}
     */
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

    /**
     * DELETE api/projects/{uuid}
     */
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
});
