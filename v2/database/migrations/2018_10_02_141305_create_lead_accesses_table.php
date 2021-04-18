<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLeadAccessesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lead_accesses', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->nullable();
            $table->unsignedInteger('lead_id');
            $table->string('answer');
            $table->timestamp('expired_at');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->index('lead_id'); // Creating index because db_res_utf8.res_lead_management engine is MyIsam (I know..)
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lead_accesses');
    }
}
