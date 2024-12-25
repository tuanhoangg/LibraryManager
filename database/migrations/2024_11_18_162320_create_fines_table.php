<?php

use App\Models\BorrowHistory;
use App\Models\Fines;
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
        Schema::create('fines', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('borrow_id')->index();
            $table->unsignedBigInteger('user_id')->index();
            $table->enum('fine_type', [BorrowHistory::STATUS_OVERDUE, BorrowHistory::STATUS_LOST, BorrowHistory::STATUS_DAMAGED]);
            $table->string('reference_id')->nullable();
            $table->unsignedBigInteger('amount');
            $table->integer('status')->default(Fines::STATUS_PENDING);
            $table->timestamps();

            $table->unique(['borrow_id', 'reference_id'], 'borrow_reference_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fines');
    }
};
