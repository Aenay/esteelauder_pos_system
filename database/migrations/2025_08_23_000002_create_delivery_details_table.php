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
        Schema::create('delivery_details', function (Blueprint $table) {
            $table->id('Delivery_Detail_ID');
            $table->foreignId('Delivery_ID')->constrained('deliveries', 'Delivery_ID')->onDelete('cascade');
            $table->foreignId('Product_ID')->constrained('products', 'Product_ID')->onDelete('cascade');
            $table->integer('Quantity_Ordered');
            $table->integer('Quantity_Received')->default(0);
            $table->decimal('Unit_Cost', 10, 2);
            $table->decimal('Total_Cost', 10, 2);
            $table->text('Notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delivery_details');
    }
};
