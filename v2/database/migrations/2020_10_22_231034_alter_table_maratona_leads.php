<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableMaratonaLeads extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('maratona_leads', function (Blueprint $table) {
            $table->string('when_purchase')->after('property_value')->nullable();
            $table->string('initial_payment')->after('when_purchase')->nullable();
            $table->string('rent')->after('initial_payment')->nullable();
            $table->string('marital_status')->after('rent')->nullable();
            $table->string('date_birth')->after('marital_status')->nullable();
            $table->string('document_cpf')->after('date_birth')->nullable();
            $table->string('document_rg')->after('document_cpf')->nullable();
            $table->string('document_rg_origin')->after('document_rg')->nullable();
            $table->string('contact_channel')->after('document_rg_origin')->nullable();
            $table->string('name', 255)->nullable()->change();
            $table->string('email', 255)->nullable()->change();
            $table->string('phone', 20)->nullable()->change();
            $table->string('interest_zone', 50)->nullable()->change();
            $table->string('property_value', 50)->nullable()->change();
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
            $table->dropColumn('when_purchase');
            $table->dropColumn('initial_payment');
            $table->dropColumn('rent');
            $table->dropColumn('marital_status');
            $table->dropColumn('date_birth');
            $table->dropColumn('document_cpf');
            $table->dropColumn('document_rg');
            $table->dropColumn('document_rg_origin');
            $table->dropColumn('contact_channel');
        });
    }
}
