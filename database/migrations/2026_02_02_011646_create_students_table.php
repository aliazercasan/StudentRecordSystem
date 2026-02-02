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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('student_id', 20)->unique();
            $table->string('full_name', 100);
            $table->string('course', 100);
            $table->tinyInteger('year_level');
            $table->string('contact_number', 15)->nullable();
            $table->string('address', 255)->nullable();
            $table->string('photo_path', 255)->nullable();
            $table->timestamps();
            
            // Indexes for better query performance
            $table->index('student_id');
            $table->index('full_name');
            $table->index('course');
            $table->index('year_level');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
