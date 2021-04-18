<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNoteAndAnsweredAtToLeadQaContactAttemptsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lead_qa_contact_attempts', function (Blueprint $table) {
            $table->timestamp('answered_at')->nullable()->after('type');
            $table->string('note')->nullable()->after('answered_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lead_qa_contact_attempts', function (Blueprint $table) {
            $table->dropColumn('answered_at');
            $table->dropColumn('note');
        });
    }
}
