<?php

use App\Models\User;
use Spatie\Permission\Models\Role;

describe('Role Management', function(){
    it('can assign roles to users', function () {

        $user = User::factory()->create();
        $user->assignRole('admin');
        $this->assertTrue($user->hasRole('admin'));
    });

    it('can remove roles from users', function(){
        $user = User::factory()->create();
        $user->assignRole('admin');
        $user->removeRole('admin');
        expect($user->hasRole('admin'))->toBeFalse();
    });

    it('has user, admin and service roles', function(){
        expect(Role::all()->pluck('name'))->toContainEqual('admin', 'user', 'service');
    });

});


