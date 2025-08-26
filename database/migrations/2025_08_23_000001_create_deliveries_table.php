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
        Schema::create('deliveries', function (Blueprint $table) {
            $table->id('Delivery_ID');
            $table->foreignId('Supplier_ID')->constrained('suppliers', 'Supplier_ID')->onDelete('cascade');
            $table->string('Delivery_Reference')->unique();
            $table->date('Expected_Delivery_Date');
            $table->date('Actual_Delivery_Date')->nullable();
            $table->enum('Status', ['pending', 'in_transit', 'delivered', 'cancelled'])->default('pending');
            $table->text('Notes')->nullable();
            $table->decimal('Total_Amount', 10, 2)->default(0);
            $table->string('Tracking_Number')->nullable();
            $table->string('Carrier')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deliveries');
    }
};
