<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyPlayerColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('players', function (Blueprint $table) {
            // 既存のカラムを修正します
            $table->string('player_number')->nullable()->change();
            $table->string('foot')->nullable()->change();
            $table->integer('goals')->default(0)->change();
            $table->string('feature')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('players', function (Blueprint $table) {
            // ロールバック時には変更を戻します（ここでは変更を元に戻すためのコードは不要です）
        });
    }
}
