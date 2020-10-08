<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FormCreditConyuge extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('form_credit_conyuge', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('first_name_spouse');
            $table->string('second_name_spouse');
            $table->string('first_last_name_spouse');
            $table->string('second_last_name_spouse');
            $table->enum('type_document_spouse', ["C.C", "C.E"]);
            $table->string('number_document_spouse');
            $table->enum('type_activity_spouse', ["Empleado", "Independiente", "Ama de Casa", "Empresario(a)", "Estudiante", "Pensionado", "Rentista de Capital"]);
            $table->string('phone_spouse');
            $table->string('company_work_spouse');
            $table->string('charge_worck_spouse');
            $table->string('monthly_income_spouse');
          
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('form_credit_conyuge');
    }
}
