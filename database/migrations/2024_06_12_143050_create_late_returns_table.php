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
        Schema::create('late_returns', function (Blueprint $table) {
            $table->id();
            $table->integer('borrow_id');
            $table->integer('user_id');
            $table->bigInteger('late_fee');
            $table->bigInteger('late_days');
            $table->tinyInteger('status'); // da nop phat, chua nop phat
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('late_returns');
    }
};
