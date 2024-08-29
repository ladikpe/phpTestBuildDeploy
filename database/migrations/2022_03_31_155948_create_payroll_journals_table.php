<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePayrollJournalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payroll_journals', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('payroll_id');
            $table->string('code');
            $table->date('date');
            $table->string('gl_code');
            $table->text('description')->nullable();
            $table->string('project_code')->nullable();
            $table->decimal('debit',15,2)->default(0);
            $table->decimal('credit',15,2)->default(0);
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
        Schema::dropIfExists('payroll_journals');
    }
}
