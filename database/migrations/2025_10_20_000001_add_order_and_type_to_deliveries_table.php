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
        Schema::table('deliveries', function (Blueprint $table) {
            // Differentiate deliveries by type and link customer deliveries to orders
            $table->enum('delivery_type', ['supplier', 'customer'])->default('supplier')->after('Supplier_ID');
            $table->foreignId('Order_ID')->nullable()->after('Delivery_Reference')
                ->constrained('orders', 'Order_ID')->onDelete('cascade');

            // Allow supplier_id to be nullable for customer deliveries
            $table->unsignedBigInteger('Supplier_ID')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('deliveries', function (Blueprint $table) {
            // Drop the foreign key and column for Order_ID
            $table->dropForeign(['Order_ID']);
            $table->dropColumn('Order_ID');

            // Drop delivery_type
            $table->dropColumn('delivery_type');

            // Revert Supplier_ID to not nullable if supported
            // Note: depending on database driver, change() to not nullable may require manual handling
            $table->unsignedBigInteger('Supplier_ID')->nullable(false)->change();
        });
    }
};