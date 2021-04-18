<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEquityLeadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('equity_leads', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email');
            $table->date('birthday');
            $table->string('phone', 17);
            $table->string('phone2', 17)->nullable();
            $table->string('cpf', 14);
            $table->string('family_income', 15);
            $table->string('professional_type', 30);
            $table->string('goal', 30);
            $table->string('zip_code', 9);
            $table->string('street_number', 10)->nullable();
            $table->unsignedInteger('property_type');
            $table->unsignedInteger('property_area');
            $table->unsignedInteger('property_bedrooms');
            $table->unsignedInteger('requested_credit');
            $table->unsignedInteger('installments');
            $table->unsignedInteger('pre_approved_credit');
            $table->tinyInteger('property_is_settled');
            $table->enum('has_property_registration', ['Sim', 'Não', 'Não sei']);
            $table->timestamps();

            $table->index('email');
            $table->index('property_type'); // Creating index because ai_core.core_propertyTypes engine is MyIsam (I know..)
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('equity_leads');
    }
}
