<?php

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
});
