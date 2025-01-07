<?php

describe('Api Setup', function(){

    it('has required database tables', function () {
        $requiredTables = [
            'users',
            'oauth_clients',
            'roles',
            'permissions',
            'role_has_permissions'
        ];

        foreach ($requiredTables as $table) {
            expect(Schema::hasTable($table))
                ->toBeTrue("Table '$table' should exist");
        }
    }); 

    it('has all user columns', function(){
        expect(Schema::getColumnListing('users'))->toContainEqual('id', 'name', 'email', 'password', 'is_service');
    });

});
