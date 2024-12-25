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
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            // $table->string('book_publiser')->nullable();
            $table->string('isbn_code')->nullable();
            $table->integer('author_id')->nullable();
            $table->integer('year')->nullable();
            $table->string('description')->nullable();
            $table->string('image')->nullable();
            $table->integer('genres_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
