<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
          RolesTableSeeder::class,
          NotificationsTableSeeder::class,
          XmlsTableSeeder::class,
          AutoDescsTableSeeder::class,
          TerrenoDescsTableSeeder::class,
          ComercialDescsTableSeeder::class
        ]);
    }
}
