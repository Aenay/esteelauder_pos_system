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
        Schema::create('purchase_details', function (Blueprint $table) {
            $table->id('Purchase_Detail_ID');
            $table->foreignId('Purchase_ID')->constrained('purchases', 'Purchase_ID');
            $table->foreignId('Product_ID')->constrained('products', 'Product_ID');
            $table->integer('Quantity');
            $table->decimal('Unit_Price', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_details');
    }
};