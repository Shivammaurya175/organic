<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up()
{
    Schema::create('levels', function (Blueprint $table) {
        $table->id();
        $table->unsignedInteger('rank_profit');     // Example: Bronze, Silver, etc.
        $table->string('rank_name');        // Display name (optional)
        $table->unsignedInteger('start_target');   // Starting volume (e.g., 1000)
        $table->unsignedInteger('end_target');     // Ending volume (e.g., 4999)
        $table->unsignedInteger('self_volume');    // Required self volume
        $table->unsignedInteger('team_volume');    // Required team volume
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('levels');
    }
};
