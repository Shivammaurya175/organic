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
        Schema::create('royalty_level', function (Blueprint $table) {
            $table->id();
            
            $table->integer('level_no'); 
            $table->integer('level_first')->default(0); 
            $table->integer('second_level')->default(0); // Required team volume
            $table->integer('three_level')->default(0); 
            $table->integer('four_level')->default(0); 
            $table->integer('fifth_level')->default(0); 
            $table->integer('six_level')->default(0); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('royalty_level');
    }
};
