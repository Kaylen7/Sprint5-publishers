<?php

use App\Models\User;
use App\Models\Project;

describe("Project Model Testing", function(){
    test('Num_pages gets updated after num_chars changes', function (){
        $user = User::factory()->create();
        $project = Project::factory()->create(['num_chars' => 2000, 'owner_id' => $user->id]);
        expect($project->id)->toBe(1);

        $initial_pages = $project->num_pages;
        $project->update(["num_chars" => 4000]);
        $updatedProject = Project::findOrFail($project->id);
        
        expect($updatedProject->num_pages)->toBe(2.0);
        expect($initial_pages)->not->toBe($updatedProject->num_pages);
    });
});

