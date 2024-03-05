<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropColumnsFromMatchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('matches', function (Blueprint $table) {
            // Drop team1_id and team2_id columns
            $table->dropColumn('team1_id');
            $table->dropColumn('team2_id');

            // Drop team1_formation and team2_formation columns
            $table->dropColumn('team1_formation');
            $table->dropColumn('team2_formation');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('matches', function (Blueprint $table) {
             // Add back team1_id and team2_id columns
            $table->unsignedBigInteger('team1_id');
            $table->unsignedBigInteger('team2_id');

            // Add back team1_formation and team2_formation columns
            $table->string('team1_formation');
            $table->string('team2_formation');
        });
    }
}
