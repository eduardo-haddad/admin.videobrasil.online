<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class NotificationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('notifications')->insert([
            [
                'alias' => 'inactive-listing',
                'name' => 'Listing Desativado',
                'description' => 'Recebe um alerta quando um listing desativado no portal recebe um lead.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        ]);
    }
}
