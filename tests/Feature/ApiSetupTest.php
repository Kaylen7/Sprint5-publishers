<?php

describe('Api Setup', function(){

    it('has required database tables', function () {
        $requiredTables = [
            'users',
            'oauth_clients',
            'roles',
            'permissions',
            'role_has_permissions',
            'projects',
            'services'
        ];

        foreach ($requiredTables as $table) {
            expect(Schema::hasTable($table))
                ->toBeTrue("Table '$table' should exist");
        }
    }); 

    it('has all user columns', function(){
        expect(Schema::getColumnListing('users'))->toContainEqual('id', 'name', 'email', 'password');
    });

    it('has all project columns', function(){
        expect(Schema::getColumnListing('projects'))->toContainEqual('id', 'uuid', 'name', 'description', 'num_chars', 'num_pages', 'owner_id', 'status', 'total_price', 'start_date', 'projected_end_date');
    });

    it('has all service columns', function(){
        expect(Schema::getColumnListing('services'))->toContainEqual('id', 'uuid', 'name', 'description', 'languages', 'user_id', 'type', 'available');
    });

});
