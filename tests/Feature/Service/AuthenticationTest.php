<?php

describe('Authentication', function(){
    dataset('service_endpoints', function(){
        $endpoint = 'api/services';
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
    })->with('service_endpoints');
});