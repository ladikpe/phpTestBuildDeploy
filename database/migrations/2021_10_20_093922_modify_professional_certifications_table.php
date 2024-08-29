<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyProfessionalCertificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('professional_certifications', function (Blueprint $table) {

            $table->integer('company_id')->nullable();
            $table->text('file')->nullable();
            $table->string('issuer_name')->nullable();
            $table->integer('last_change_approved')->nullable();
            $table->integer('last_change_approved_by')->nullable();
            $table->date('last_change_approved_on')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('professional_certifications', function (Blueprint $table) {

            $table->dropColumn('company_id');
            $table->dropColumn('file');
            $table->dropColumn('issuer_name');
            $table->dropColumn('last_change_approved');
            $table->dropColumn('last_change_approved_by');
            $table->dropColumn('last_change_approved_on');
        });
    }
}
