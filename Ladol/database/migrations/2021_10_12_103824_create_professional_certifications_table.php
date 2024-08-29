<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProfessionalCertificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('professional_certifications', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('credential_id');
            $table->integer('expires')->default(0);
            $table->date('issued_on');
            $table->date('expires_on')->nullable();
            $table->integer('emp_id');

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
        Schema::dropIfExists('professional_certifications');
    }
}
