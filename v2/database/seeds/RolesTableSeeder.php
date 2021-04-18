<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
            [
                'alias' => 'root',
                'name' => 'Super Admin',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'alias' => 'campaign-manager',
                'name' => 'Gerente de Campanha',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'alias' => 'lead-manager',
                'name' => 'Gerente de Lead',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'alias' => 'avm-manager',
                'name' => 'Gerente de AVM',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'alias' => 'website-editor',
                'name' => 'Editor do Website',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'alias' => 'broker-lp',
                'name' => 'Broker: Lead Page',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        ]);
    }
}
