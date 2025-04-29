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
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('surname');
            $table->string('patronymic')->nullable();
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->string('birthday')->nullable();
            $table->string('email')->unique();
            $table->string('password')->nullable();
            $table->integer('role_id')->default(1);
            $table->uuid('group_id')->nullable();
            $table->uuid('organization_id');
            $table->boolean('isRegister')->default(false);
            $table->boolean('isArchive')->default(false);
            $table->rememberToken();
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
