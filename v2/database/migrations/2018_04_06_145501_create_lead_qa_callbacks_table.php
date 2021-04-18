<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLeadQaCallbacksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lead_qa_callbacks', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('lead_qa_id');
            $table->timestamp('made_at');
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
        Schema::dropIfExists('lead_qa_callbacks');
    }
}
