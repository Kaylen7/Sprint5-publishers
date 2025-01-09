<?php
use App\Models\User;

describe('User CRUD', function(){
    it('requires authentication for user listing', function() {
        $response = $this->getJson('api/users');
        $response->assertStatus(401);
    });
});