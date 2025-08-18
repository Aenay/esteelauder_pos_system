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
        Schema::create('purchases', function (Blueprint $table) {
            $table->id('Purchase_ID');
            $table->date('Purchase_Date');
            $table->foreignId('Supplier_ID')->constrained('suppliers', 'Supplier_ID');
            $table->decimal('Total_Amount', 10, 2);
            $table->foreignId('Staff_ID')->constrained('staff', 'Staff_ID');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};