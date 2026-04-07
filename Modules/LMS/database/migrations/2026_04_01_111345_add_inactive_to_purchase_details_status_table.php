<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add 'inactive' to the status enum list in purchase_details table for MySQL
        DB::statement("ALTER TABLE purchase_details MODIFY COLUMN status ENUM('processing', 'completed', 'pending', 'inactive') DEFAULT 'processing'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to original enum values (warning: can cause data truncation if 'inactive' exists)
        DB::statement("ALTER TABLE purchase_details MODIFY COLUMN status ENUM('processing', 'completed', 'pending') DEFAULT 'processing'");
    }
};
