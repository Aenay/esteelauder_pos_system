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
        Schema::create('orders', function (Blueprint $table) {
            $table->id('Order_ID');
            $table->date('Order_Date');
            $table->foreignId('Staff_ID')->constrained('staff', 'Staff_ID');
            $table->foreignId('Customer_ID')->constrained('customers', 'Customer_ID');
            $table->foreignId('Promotion_ID')->nullable()->constrained('promotions', 'Promotion_ID');
            $table->decimal('Subtotal', 10, 2);
            $table->decimal('Discount_Amount', 10, 2)->default(0);
            $table->decimal('Final_Amount', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};