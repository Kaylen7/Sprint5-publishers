<?php
use App\Models\Permission;

describe('Permissions', function(){
    it('has basic permissions set up', function () {
        expect(count(Permission::all()))->toBeGreaterThan(0);
    });
});

