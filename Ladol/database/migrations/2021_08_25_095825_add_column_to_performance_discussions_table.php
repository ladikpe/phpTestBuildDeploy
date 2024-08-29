<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnToPerformanceDiscussionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('performance_discussions', function (Blueprint $table) {
            $table->integer('employee_submitted')->nullable();
            $table->integer('line_manager_approved')->nullable();
            $table->date('line_manager_approval_date')->nullable();
            $table->date('employee_submission_date')->nullable();
            $table->text('rejection_reason')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('performance_discussions', function (Blueprint $table) {
            $table->dropColumn('employee_submitted');
            $table->dropColumn('line_manager_approved');
            $table->dropColumn('line_manager_approval_date');
            $table->dropColumn('employee_submission_date');
            $table->dropColumn('rejection_reason');
        });
    }
}
