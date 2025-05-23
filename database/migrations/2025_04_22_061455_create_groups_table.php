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
        Schema::create('groups', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->integer('course');
            $table->integer('max_course')->nullable();
            $table->date('admission_date');
            $table->uuid('classroomTeacher_id')->nullable();
            $table->uuid('speciality_id')->nullable();
            $table->uuid('organization_id');
            $table->boolean('isArchive')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('groups');
    }
};
