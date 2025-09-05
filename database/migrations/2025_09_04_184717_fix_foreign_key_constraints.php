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
        // Drop existing foreign key constraints
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['Customer_ID']);
            $table->dropForeign(['Staff_ID']);
            $table->dropForeign(['Promotion_ID']);
        });

        // Recreate foreign key constraints with cascade delete
        Schema::table('orders', function (Blueprint $table) {
            $table->foreign('Customer_ID')->references('Customer_ID')->on('customers')->onDelete('cascade');
            $table->foreign('Staff_ID')->references('Staff_ID')->on('staff')->onDelete('cascade');
            $table->foreign('Promotion_ID')->references('Promotion_ID')->on('promotions')->onDelete('set null');
        });

        // Fix other foreign key constraints that might have similar issues
        // Check if loyalty_points table exists and fix its constraints
        if (Schema::hasTable('loyalty_points')) {
            Schema::table('loyalty_points', function (Blueprint $table) {
                $table->dropForeign(['Customer_ID']);
            });
            
            Schema::table('loyalty_points', function (Blueprint $table) {
                $table->foreign('Customer_ID')->references('Customer_ID')->on('customers')->onDelete('cascade');
            });
        }

        // Fix order_details foreign key constraints
        if (Schema::hasTable('order_details')) {
            Schema::table('order_details', function (Blueprint $table) {
                $table->dropForeign(['Order_ID']);
                $table->dropForeign(['Product_ID']);
            });
            
            Schema::table('order_details', function (Blueprint $table) {
                $table->foreign('Order_ID')->references('Order_ID')->on('orders')->onDelete('cascade');
                $table->foreign('Product_ID')->references('Product_ID')->on('products')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop the new foreign key constraints
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['Customer_ID']);
            $table->dropForeign(['Staff_ID']);
            $table->dropForeign(['Promotion_ID']);
        });

        // Recreate original foreign key constraints without cascade
        Schema::table('orders', function (Blueprint $table) {
            $table->foreign('Customer_ID')->references('Customer_ID')->on('customers');
            $table->foreign('Staff_ID')->references('Staff_ID')->on('staff');
            $table->foreign('Promotion_ID')->references('Promotion_ID')->on('promotions');
        });

        // Revert loyalty_points changes
        if (Schema::hasTable('loyalty_points')) {
            Schema::table('loyalty_points', function (Blueprint $table) {
                $table->dropForeign(['Customer_ID']);
            });
            
            Schema::table('loyalty_points', function (Blueprint $table) {
                $table->foreign('Customer_ID')->references('Customer_ID')->on('customers');
            });
        }

        // Revert order_details changes
        if (Schema::hasTable('order_details')) {
            Schema::table('order_details', function (Blueprint $table) {
                $table->dropForeign(['Order_ID']);
                $table->dropForeign(['Product_ID']);
            });
            
            Schema::table('order_details', function (Blueprint $table) {
                $table->foreign('Order_ID')->references('Order_ID')->on('orders');
                $table->foreign('Product_ID')->references('Product_ID')->on('products');
            });
        }
    }
};