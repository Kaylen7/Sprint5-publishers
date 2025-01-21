<?php
use App\Models\User;
use App\Models\Project;

describe('User Model methods', function(){

    test('hasProjects() returns owned projects', function(){
        $userOne = User::factory()->create();
        $userTwo = User::factory()->create();
        expect($userOne->id)->toBe(1);
        expect($userTwo->id)->toBe(2);        
        expect($userOne->hasProjects()->count())->toBe(0);

        $project = Project::factory()->create([
            'owner_id' => $userOne->id
        ]);
        expect($project->id)->toBe(1);
        $projectTwo = Project::factory()->create([
            'owner_id' => $userTwo->id
        ]);
        expect($projectTwo->id)->toBe(2);
        
        $owned_projects = $userOne->hasProjects();
        expect($owned_projects->count())->toBe(1);
        expect(Project::all()->count())->toBe(2);
    });
});
