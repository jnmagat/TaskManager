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
        Schema::table('tasks', function (Blueprint $table) {
            // Add indexes to speed up common lookups/filters:
            $table->index('category_id');
            $table->index('priority_id');
            $table->index('status_id');
            $table->index('due_date');
        });
    }

    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropIndex(['category_id']);
            $table->dropIndex(['priority_id']);
            $table->dropIndex(['status_id']);
            $table->dropIndex(['due_date']);
        });
    }
};
