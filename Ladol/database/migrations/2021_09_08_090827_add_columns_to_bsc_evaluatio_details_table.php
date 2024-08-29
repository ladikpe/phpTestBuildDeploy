<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToBscEvaluatioDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bsc_evaluation_details', function (Blueprint $table) {
            $table->decimal('score',8,2)->nullable();
            $table->text('employee_comment')->nullable();
        });
        Schema::table('bsc_evaluations', function (Blueprint $table) {
            $table->integer('is_disputed')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bsc_evaluation_details', function (Blueprint $table) {
            $table->dropColumn('score');
            $table->dropColumn('employee_comment');
        });
        Schema::table('bsc_evaluations', function (Blueprint $table) {
            $table->dropColumn('is_disputed');
        });
    }
}
