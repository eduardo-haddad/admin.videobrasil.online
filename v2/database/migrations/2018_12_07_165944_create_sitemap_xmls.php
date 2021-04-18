<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSitemapXmls extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::table('xmls', function (Blueprint $table) {
        //     $table->unsignedInteger('num_files')->nullable();
        //     $table->string('index', 45)->nullable();
        //     $table->string('frequency', 45)->nullable();
        //     $table->unsignedInteger('item_count')->nullable();
        // });
        

        // Schema::create('xmls_indexes', function (Blueprint $table) {
        //     $table->increments('id');
        //     $table->string('index',45);
        //     $table->unsignedInteger('xmls_id');
        //     $table->string('url');
        //     $table->timestamps();
        // });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::dropIfExists('xmls_indexes');
        // Schema::table('xmls', function (Blueprint $table) {
        //     $table->dropColumn('num_files');
        //     $table->dropColumn('index');
        //     $table->dropColumn('frequency');
        //     $table->dropColumn('item_count');
        // });
    }
}
