<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('members', function (Blueprint $table) {
            //
            // $table->renameColumn('level', 'membership_plan_id');
            DB::statement('ALTER TABLE members CHANGE level membership_plan_id INT');

        });
        Schema::table('users', function (Blueprint $table) {
            //
            $table->string('member_code')->nullable();
            // $table->renameColumn('member', 'member_id');
            DB::statement('ALTER TABLE users CHANGE member member_id INT');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table('users', function (Blueprint $table) {
            //
            $table->dropColumn('member_code');
        });
    }
};
