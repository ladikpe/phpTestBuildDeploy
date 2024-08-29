<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnToLoanPoliciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('loan_policies', 'specific_salary_component_type_id')) {
        Schema::table('loan_policies', function (Blueprint $table) {
            $table->integer('specific_salary_component_type_id')->nullable();


        });
    }
        if (!Schema::hasColumn('loan_requests', 'maximum_allowed')) {
            Schema::table('loan_requests', function (Blueprint $table) {
                $table->decimal('maximum_allowed',10,2)->nullable();
            });
        }
        if (!Schema::hasColumn('loan_requests', 'total_repayments')) {
            Schema::table('loan_requests', function (Blueprint $table) {
                $table->decimal('total_repayments',10,2)->nullable();
            });
        }
        if (!Schema::hasColumn('loan_requests', 'total_interest')) {
            Schema::table('loan_requests', function (Blueprint $table) {
                $table->decimal('total_interest',10,2)->nullable();
            });
        }
        if (!Schema::hasColumn('loan_requests', 'workflow_id')) {
            Schema::table('loan_requests', function (Blueprint $table) {
                $table->integer('workflow_id')->nullable();
            });
        }
        if (!Schema::hasColumn('loan_policies', 'concurrent_loans')) {
            Schema::table('loan_policies', function (Blueprint $table) {
                $table->integer('concurrent_loans')->nullable();
            });
        }
        if (!Schema::hasColumn('loan_policies', 'repayment_length')) {
            Schema::table('loan_policies', function (Blueprint $table) {
                $table->integer('repayment_length')->nullable();
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
        Schema::table('loan_policies', function (Blueprint $table) {
           $table->dropColumn('specific_salary_component_type_id');
            $table->dropColumn('concurrent_loans');
        });
        Schema::table('loan_requests', function (Blueprint $table) {
            $table->dropColumn('maximum_allowed');
            $table->dropColumn('total_repayments');
            $table->dropColumn('total_interest');
            $table->dropColumn('workflow_id');
        });
    }
}
