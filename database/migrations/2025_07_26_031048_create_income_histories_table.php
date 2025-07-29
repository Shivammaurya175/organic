<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('income_histories', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Income earner
    $table->string('type'); // level_income, rank_bonus, referral_bonus, etc.
    $table->foreignId('from_user_id')->nullable()->constrained('users')->onDelete('set null'); // Who generated this income
    $table->float('amount')->default(0);
    $table->text('note')->nullable();
    $table->timestamp('earned_at')->default(DB::raw('CURRENT_TIMESTAMP'));
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('income_histories');
    }
};
