<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEmailWorksToLeadQasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lead_qas', function (Blueprint $table) {
            $table->tinyInteger('email_works')->nullable()->after('phone_works');
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
            $table->dropColumn('email_works');
        });
    }
}
