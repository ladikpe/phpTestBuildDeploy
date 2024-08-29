<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyLoanPoliciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('loan_policies');
        Schema::create('loan_policies', function(Blueprint $table){
            $table->increments('id');
            $table->decimal('annual_interest_percentage', 10, 2)->nullable();
            $table->integer('uses_confirmation')->nullable();
            $table->integer('uses_length_of_stay')->nullable();
            $table->integer('minimum_length_of_stay')->default('0');
            $table->integer('uses_performance')->nullable();
            $table->integer('minimum_performance_mark')->nullable();
            $table->decimal('dsr_percentage', 10, 2)->nullable();
            $table->integer('user_id')->nullable()->default('0');
            $table->integer('company_id')->default('0');
            $table->integer('workflow_id')->default('0');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('loan_policies');
    }
}
