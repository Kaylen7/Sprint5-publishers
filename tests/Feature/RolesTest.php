<?php

use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\DB; 
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('user can be assigned role', function () {

    $user = User::factory()->create();

    $role = Role::create(['name' => 'admin']);

    $user->assignRole('admin');

    $this->assertTrue($user->hasRole('admin'));
});
