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
        Schema::create('loyalty_points', function (Blueprint $table) {
            $table->id('Loyalty_ID');
            $table->foreignId('Customer_ID')->constrained('customers', 'Customer_ID')->onDelete('cascade');
            $table->integer('points_earned')->default(0);
            $table->integer('points_used')->default(0);
            $table->integer('current_balance')->default(0);
            $table->string('tier_level')->default('bronze'); // bronze, silver, gold, platinum
            $table->date('last_activity_date');
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index(['Customer_ID', 'tier_level']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loyalty_points');
    }
};

