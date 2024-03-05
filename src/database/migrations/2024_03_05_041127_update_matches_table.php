<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateMatchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('matches', function (Blueprint $table) {
            // カラムを削除
            $table->dropColumn('opponent');
            $table->dropColumn('updated_at');
            $table->dropColumn('result');
            $table->dropColumn('position');
            $table->dropColumn('comment');

            // 新しいカラムを追加
            $table->string('location')->nullable()->after('date');
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
            //
        });
    }
}
