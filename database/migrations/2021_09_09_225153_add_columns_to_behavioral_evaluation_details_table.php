<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToBehavioralEvaluationDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('behavioral_evaluation_details', function (Blueprint $table) {
            $table->decimal('score',8,2)->nullable();
            $table->text('employee_comment')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('behavioral_evaluation_details', function (Blueprint $table) {
            $table->dropColumn('score'); 
            $table->dropColumn('employee_comment'); 
        });
    }
}
