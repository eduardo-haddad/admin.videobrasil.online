<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCallbackScheduledAtToLeadQasTable extends Migration
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
                $table->timestamp('callback_scheduled_at')->nullable()->after('make_contact');
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
                $table->dropColumn('callback_scheduled_at');
            });
        });
    }
}
