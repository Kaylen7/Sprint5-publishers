<?php

use App\Models\Role;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        //Create essential roles
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'user']);
        Role::create(['name' => 'service']);

        //Create essential permissions

        //Assign permissions to roles
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Role::findByName('admin')?->delete();
    }
};
