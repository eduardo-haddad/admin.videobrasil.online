<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLeadQaAttemptsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lead_qa_attempts', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('lead_qa_id');
            $table->enum('channel', ['w', 't']);
            $table->timestamps();

            $table->foreign('lead_qa_id')->references('id')->on('lead_qas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lead_qa_attempts');
    }
}
