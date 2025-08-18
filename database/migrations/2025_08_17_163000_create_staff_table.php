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
        Schema::create('staff', function (Blueprint $table) {
            $table->id('Staff_ID');
            $table->string('Staff_Name');
            $table->string('Staff_Phone')->nullable();
            $table->string('Staff_Address')->nullable();
            $table->string('email')->unique();
            $table->string('password');
            $table->foreignId('department_id')->constrained('departments', 'Department_ID');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff');
    }
};