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
        Schema::table('customers', function (Blueprint $table) {
            $table->string('Customer_Email')->nullable()->after('Customer_Address');
            $table->string('Customer_Type')->default('internal')->after('Customer_Email');
            $table->date('Registration_Date')->nullable()->after('Customer_Type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn(['Customer_Email', 'Customer_Type', 'Registration_Date']);
        });
    }
};
