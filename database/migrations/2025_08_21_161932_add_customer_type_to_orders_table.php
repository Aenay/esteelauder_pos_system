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
            // Make Customer_ID nullable
            $table->foreignId('Customer_ID')->nullable()->change();
            // Add customer_type column
            $table->string('customer_type')->after('Customer_ID')->default('external'); // 'internal' or 'external'
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Revert Customer_ID to not nullable (if it was originally not nullable)
            // This might require dropping and re-adding the foreign key constraint if it was not nullable initially
            // For simplicity, we'll assume it was originally nullable or handle it carefully.
            // If you need to revert to non-nullable, you might need to remove the foreign key first, then change, then re-add.
            // $table->foreignId('Customer_ID')->nullable(false)->change(); // Use with caution

            // Drop the customer_type column
            $table->dropColumn('customer_type');
        });
    }
};