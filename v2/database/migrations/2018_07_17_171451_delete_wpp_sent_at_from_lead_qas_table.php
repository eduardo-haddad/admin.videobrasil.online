<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DeleteWppSentAtFromLeadQasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lead_qas', function (Blueprint $table) {
             $table->dropColumn(['wpp_sent_at', 'phone_called_at']);
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
            $table->timestamp('wpp_sent_at')->nullable()->after('lead_id');
            $table->timestamp('phone_called_at')->nullable()->after('wpp_sent_at');
        });
    }
}
