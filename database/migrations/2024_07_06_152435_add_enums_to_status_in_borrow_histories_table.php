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
            $table->enum('status', ['pending', 'approved', 'rejected', 'borrowed', 'returned', 'overdue', 'lost', 'damaged', 'unresrve'])->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('book_item', function (Blueprint $table) {
            //
        });
    }
};
