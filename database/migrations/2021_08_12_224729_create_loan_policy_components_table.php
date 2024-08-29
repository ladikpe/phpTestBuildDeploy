<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLoanPolicyComponentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loan_policy_components', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('payroll_policy_id');
            $table->string('source');
            $table->string('salary_component_constant')->nullable();
            $table->string('payroll_constant')->nullable();
            $table->decimal('amount',10,2)->nullable();
            $table->integer('percentage')->nullable();
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
        Schema::dropIfExists('loan_policy_components');
    }
}
