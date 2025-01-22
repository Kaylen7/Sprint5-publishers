<?php

describe('authentication', function(){
    dataset('user_endpoints', function(){
        $endpoint = 'api/users';
        return [
            ['getJson', $endpoint],
            ['getJson', $endpoint . '/1'],
            ['putJson', $endpoint . '/1'],
            ['deleteJson', $endpoint . '/1']
        ];
    });
    test('endpoints require authentication', function($method, $endpoint) {
        $this->$method($endpoint)->assertStatus(401);
    })->with('user_endpoints');
});