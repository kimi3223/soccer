<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyMatchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('matches', function (Blueprint $table) {
            $table->dropColumn(['result1', 'position1', 'result2', 'position2', 'result3', 'position3', 'result4', 'position4']);
            $table->string('result')->nullable();
            $table->string('position')->nullable();
            
            // 既存の comment カラムが存在しない場合のみ追加する
            if (!Schema::hasColumn('matches', 'comment')) {
                $table->string('comment')->nullable();
            }
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
            $table->string('result1')->nullable();
            $table->string('position1')->nullable();
            $table->string('result2')->nullable();
            $table->string('position2')->nullable();
            $table->string('result3')->nullable();
            $table->string('position3')->nullable();
            $table->string('result4')->nullable();
            $table->string('position4')->nullable();
            
            // 既存の comment カラムが存在しない場合のみ削除する
            if (Schema::hasColumn('matches', 'comment')) {
                $table->dropColumn('comment');
            }
            
            $table->dropColumn(['result', 'position']);
        });
    }
}
