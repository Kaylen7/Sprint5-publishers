<?php

describe('Api-scoped tests', function(){

    test('server returns a successful response', function () {
        $this->get('/')
            ->assertStatus(200);
    });

    it('handles unauthorized requests', function(){
        $this->get('/api/users')
        ->assertStatus(401);
    });

});


