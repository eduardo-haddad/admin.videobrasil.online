<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMadeVisitToLeadQasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lead_qas', function (Blueprint $table) {
            //
            $table->enum('made_visit', ['s', 'n', 'sr'])->nullable()->after('booked_visit');
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
            //
            $table->dropColumn('made_visit');
        });
    }
}
