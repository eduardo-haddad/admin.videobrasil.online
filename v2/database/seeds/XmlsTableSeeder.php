<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class XmlsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('xmls')->insert([
            [
                'name' => 'Smartly',
                'frequency' => 'daily',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'Sitemap Avm',
                'index' => 'sitemapavm_owner',
                'frequency' => 'monthly',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
            ,
            [
                'name' => 'Sitemap Avm Building',
                'index' => 'sitemapavm',
                'frequency' => 'monthly',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
            ,
            [
                'name' => 'Sitemap Avm Street',
                'index' => 'sitemapavm',
                'frequency' => 'monthly',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
            ,
            [
                'name' => 'Sitemap Avm District',
                'index' => 'sitemapavm',
                'frequency' => 'monthly',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
            ,
            [
                'name' => 'Sitemap',
                'index' => 'sitemap_owner',
                'frequency' => 'daily',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
            ,
            [
                'name' => 'City for Lancamento',
                'index' => 'sitemap',
                'frequency' => 'daily',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
            ,
            [
                'name' => 'City for Rent',
                'index' => 'sitemap',
                'frequency' => 'daily',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
            ,
            [
                'name' => 'City for Sale',
                'index' => 'sitemap',
                'frequency' => 'daily',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
            ,
            [
                'name' => 'City Ptype for Rent',
                'index' => 'sitemap',
                'frequency' => 'daily',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
            ,
            [
                'name' => 'City Ptype for Sale',
                'index' => 'sitemap',
                'frequency' => 'daily',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
            ,
            [
                'name' => 'District For Lancamento',
                'index' => 'sitemap',
                'frequency' => 'daily',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
            ,
            [
                'name' => 'District For Rent',
                'index' => 'sitemap',
                'frequency' => 'daily',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
            ,
            [
                'name' => 'District For Sale',
                'index' => 'sitemap',
                'frequency' => 'daily',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
            ,
            [
                'name' => 'District Ptype for Rent',
                'index' => 'sitemap',
                'frequency' => 'daily',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
            ,
            [
                'name' => 'District Ptype for Sale',
                'index' => 'sitemap',
                'frequency' => 'daily',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
            ,
            [
                'name' => 'Street For Rent',
                'index' => 'sitemap',
                'frequency' => 'daily',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
            ,
            [
                'name' => 'Street For Sale',
                'index' => 'sitemap',
                'frequency' => 'daily',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        ]);
    }
}
