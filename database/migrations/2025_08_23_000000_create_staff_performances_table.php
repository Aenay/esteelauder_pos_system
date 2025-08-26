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
        Schema::create('staff_performances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('Staff_ID')->constrained('staff', 'Staff_ID')->onDelete('cascade');
            $table->date('performance_date');
            $table->decimal('daily_sales_target', 10, 2)->default(0);
            $table->decimal('actual_sales', 10, 2)->default(0);
            $table->integer('orders_processed')->default(0);
            $table->integer('customers_served')->default(0);
            $table->decimal('customer_satisfaction', 3, 2)->default(0); // 0.00 to 5.00
            $table->integer('performance_rating')->default(0); // 1-5 scale
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->unique(['Staff_ID', 'performance_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff_performances');
    }
};
