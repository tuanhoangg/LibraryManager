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
        Schema::table('borrow_histories', function (Blueprint $table) {
            //
            $table->enum('status', ['pending', 'approved', 'rejected', 'borrowed', 'returned', 'overdue', 'lost', 'damaged'])->change();

            $table->timestamp('borrow_date')->change();
            $table->timestamp('due_date')->nullable()->change();
            $table->timestamp('returned_at')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('borrow_histories', function (Blueprint $table) {
            //
        });
    }
};
