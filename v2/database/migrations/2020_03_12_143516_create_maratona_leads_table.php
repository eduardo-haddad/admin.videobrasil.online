<?php

/**
 * Laravel Schematics
 *
 * WARNING: removing @tag value will disable automated removal
 *
 * @package Laravel-schematics
 * @author  Maarten Tolhuijs <mtolhuys@protonmail.com>
 * @url     https://github.com/mtolhuys/laravel-schematics
 * @tag     laravel-schematics-maratona_leads-model
 */

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMaratonaLeadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('maratona_leads', static function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 255);
            $table->string('email', 255)->unique();
            $table->string('phone', 20);
            $table->string('interest_zone', 50);
            $table->string('works', 3)->nullable();
            $table->string('married', 1)->nullable();
            $table->string('married_works', 1)->nullable();
            $table->string('compose_rent', 1)->nullable();
            $table->string('compose_rent_works', 3)->nullable();
            $table->string('cep', 10)->nullable();
            $table->string('city', 100)->nullable();
            $table->string('street', 255)->nullable();
            $table->string('neighborhood', 100)->nullable();
            $table->string('number', 10)->nullable();
            $table->string('complement', 100)->nullable();
            $table->string('origin_state', 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('maratona_leads');
    }
}
