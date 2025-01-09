<?php
use App\Models\Role;
use App\Models\Permission;

describe('Permissions', function(){
    it('has basic permissions set up', function () {
        //Create permissions
        Permission::create(['name' => 'manage users']);

        //Assign permissions to roles
        Role::findByName('admin')->givePermissionTo('manage users');

        expect(count(Permission::all()))->toBeGreaterThan(0);
    });
});

