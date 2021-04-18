<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFirstContactAtToLeadQasTable extends Migration
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
            Schema::table('lead_qas', function (Blueprint $table) {
                $table->timestamp('first_contact_at')->nullable()->after('first_talk_at');
            });
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
            Schema::table('lead_qas', function (Blueprint $table) {
                $table->dropColumn('first_contact_at');
            });
        });
    }
}
