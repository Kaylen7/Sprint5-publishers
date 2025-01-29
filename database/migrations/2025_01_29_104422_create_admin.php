<?php

use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $email = config('app.admin_email');
        $password = config('app.admin_password');

        $role = Role::firstOrCreate(['name' => 'admin']);

        $admin = User::firstOrCreate(
            ['email' => $email],
            ['password' => Hash::make($password)]
        );

        if(!$admin->hasRole('admin')){
            $admin->assignRole($role);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        User::whereHas('roles', function($query){
            $query->where('name', 'admin');
        })->delete();
    }
};
