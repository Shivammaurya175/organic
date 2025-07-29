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
        Schema::table('users', function (Blueprint $table) {
    $table->string('user_id',50)->unique();
    $table->string('decript_password')->nullable();
    $table->string('referral_id',25)->nullable(); // This is the parent user ID
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
            $table->dropColumn('user_id');
            $table->dropColumn('referral_id');
        });
    }
};
