<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->uuid()->unique();
            $table->string('name');
            $table->text('description');
            $table->integer('num_chars')->unsigned();
            $table->float('num_pages')->unsigned();
            $table->foreignId('owner_id');
            $table->enum('status', ['pending', 'ongoing', 'done'])->default('pending');
            $table->decimal('total_price')->unsigned()->nullable();
            $table->date('start_date');
            $table->date('projected_end_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
