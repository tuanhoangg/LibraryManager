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
        Schema::table('book_items', function (Blueprint $table) {
            //
            $table->string("preview_url")->after("edition")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('book_items', function (Blueprint $table) {
            //
            $table->dropColumn("preview_url");
        });
    }
};
