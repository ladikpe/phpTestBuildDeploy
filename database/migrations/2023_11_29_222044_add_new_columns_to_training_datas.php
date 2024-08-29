<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewColumnsToTrainingDatas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('training_datas', function (Blueprint $table) {
            $table->string('mode_of_training')->nullable();
            $table->dropColumn('is_external');
            $table->dropColumn('is_internal');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('training_datas', function (Blueprint $table) {
            $table->dropColumn('mode_of_training');
            $table->string('is_external');
            $table->string('is_internal');
        });
    }
}
