<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMakeContactToLeadQasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lead_qas', function (Blueprint $table) {
            $table->boolean('make_contact')->nullable()->after('purchased_product');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lead_qas', function (Blueprint $table) {
            $table->dropColumn('make_contact');
        });
    }
}