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
        Schema::table('orders', function (Blueprint $table) {
            // Drop the existing foreign key constraint
            $table->dropForeign(['Promotion_ID']);
            
            // Recreate with cascade delete
            $table->foreign('Promotion_ID')->references('Promotion_ID')->on('promotions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Drop the cascade foreign key
            $table->dropForeign(['Promotion_ID']);
            
            // Recreate without cascade delete
            $table->foreign('Promotion_ID')->references('Promotion_ID')->on('promotions');
        });
    }
};
