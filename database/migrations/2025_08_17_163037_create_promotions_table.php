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
        Schema::create('promotions', function (Blueprint $table) {
            $table->id('Promotion_ID');
            $table->string('Promotion_Name');
            $table->string('Description')->nullable();
            $table->string('Discount_Type');
            $table->decimal('Discount_Value', 10, 2);
            $table->date('Start_Date')->nullable();
            $table->date('End_Date')->nullable();
            $table->boolean('Is_Active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promotions');
    }
};