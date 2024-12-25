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
        Schema::table('late_returns', function (Blueprint $table) {
            //
            $table->unique(['borrow_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('late_returns', function (Blueprint $table) {
            //
            $table->dropUnique(['borrow_id', 'user_id']);
        });
    }
};
