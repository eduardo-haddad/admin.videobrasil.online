<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Query\Expression;
use Illuminate\Database\Migrations\Migration;

class CreateLeadQasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lead_qas', function (Blueprint $table) {
            $options1 = ['s', 'n', 'p', 'sr'];
            $options2 = ['s', 'n', 'sr'];

            $table->increments('id');
            $table->unsignedInteger('user_id')->nullable();
            $table->unsignedInteger('lead_id')->unique(); // Creating index because db_res_utf8.res_lead_management engine is MyIsam (I know..)
            $table->timestamp('wpp_sent_at')->nullable();
            $table->timestamp('phone_called_at')->nullable();
            $table->enum('phone_works', ['t', 'w', 'd', 'cp', 'n'])->nullable();
            $table->enum('talk_channel', ['w', 't'])->nullable();
            $table->timestamp('first_talk_at')->nullable();
            $table->timestamp('hotlead')->nullable();
            $table->enum('talked_to_broker', $options1)->nullable();
            $table->enum('booked_visit', $options1)->nullable();
            $table->enum('searching_immobile', $options1)->nullable();
            $table->enum('purchase_started', $options1)->nullable();
            $table->string('purchase_started_product')->nullable();
            $table->string('service_rate', 2)->nullable();
            $table->enum('visited', $options2)->nullable();
            $table->enum('purchased', $options2)->nullable();
            $table->string('purchased_product')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lead_qas');
    }
}
