<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameLeadQaAttemptsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Rename table
        Schema::rename('lead_qa_attempts', 'lead_qa_contact_attempts');

        // Add type column
        Schema::table('lead_qa_contact_attempts', function (Blueprint $table) {
            $table->string('type', 30)->after('channel');
        });

        // Update current Attempts with type
        \DB::table('lead_qa_contact_attempts')
          ->update(['type' => 'App\Lead\Qa\Attempt']);

        // Get all registered Callbacks
        $callbacks = \DB::table('lead_qa_callbacks')
                       ->select('lead_qa_id', 'made_at', 'updated_at')
                       ->get();

        $callbacks->each(function($callback){
            // Move Callbacks to lead_qa_contact_attempts table
            DB::table('lead_qa_contact_attempts')->insert([
                'lead_qa_id' => $callback->lead_qa_id,
                'type' => 'App\\Lead\\Qa\\Callback',
                'created_at' => $callback->made_at,
                'updated_at' => $callback->updated_at
            ]);
        });

        // Drop old lead_qa_callbacks table
        Schema::drop('lead_qa_callbacks');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Recreate lead_qa_callbacks table
        Schema::create('lead_qa_callbacks', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('lead_qa_id');
            $table->timestamp('made_at');
            $table->timestamps();

            $table->foreign('lead_qa_id')->references('id')->on('lead_qas')->onDelete('cascade');
        });

        // Get all registered Callbacks
        $callbacks = \DB::table('lead_qa_contact_attempts')
                       ->select('lead_qa_id, created_at, updated_at')
                       ->where('type', 'App\Lead\Qa\Callback')
                       ->get();

        $callbacks->each(function($callback){
            // Move Callbacks to lead_qa_callbacks table
            \DB::table('lead_qa_callbacks')->insert([
                'lead_qa_id' => $callback->lead_qa_id,
                'made_at' => $callback->created_at,
                'created_at' => $callback->created_at,
                'updated_at' => $callback->updated_at
            ]);
        });

        // Drop type column
        Schema::table('lead_qa_contact_attempts', function (Blueprint $table) {
            $table->dropColumn('type');
        });

        // Rename table
        Schema::rename('lead_qa_contact_attempts', 'lead_qa_attempts');
    }
}
