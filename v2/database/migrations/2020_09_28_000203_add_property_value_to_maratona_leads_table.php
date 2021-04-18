<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPropertyValueToMaratonaLeadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('maratona_leads', function (Blueprint $table) {
            $table->string('property_value')->after('origin_state');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('maratona_leads', function (Blueprint $table) {
            $table->dropColumn('property_value');
        });
    }
}
