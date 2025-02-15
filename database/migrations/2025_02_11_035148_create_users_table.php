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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('password');
            $table->string('email')->unique();
            $table->string('address')->nullable();
            $table->string(column: 'role')->nullable();
            $table->string('phone')->nullable();
            $table->string('avatar')->default('https://s.net.vn/76Th');
            $table->string('weight')->nullable();
            $table->string('height')->nullable();
            $table->string('shirt_size')->nullable();
            $table->string('pant_size')->nullable();
            $table->string('gender')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};