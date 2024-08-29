<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnToLeavePoliciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('leave_policies', 'specific_salary_component_type_id')) {
            Schema::table('leave_policies', function (Blueprint $table) {
                $table->decimal('specific_salary_component_type_id',10,2)->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('leave_policies', function (Blueprint $table) {
            $table->dropColumn('specific_salary_component_type_id');
        });
    }
}
