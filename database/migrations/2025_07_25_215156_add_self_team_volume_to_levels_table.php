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
    Schema::table('levels', function (Blueprint $table) {
        $table->unsignedInteger('self_team_volume')->after('team_volume')->default(0);
    });
}

public function down()
{
    Schema::table('levels', function (Blueprint $table) {
        $table->dropColumn('self_team_volume');
    });
}

};
