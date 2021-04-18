<?php

namespace App\XmlIntegration;

use EmailNew;
use Illuminate\Database\Eloquent\Model;
use SystemDatabaseManager;

class ZapCrawler extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'res_temp_zap_crawler';

    protected $connection = 'db_res_utf8';

    /**
     * The primary key column.
     *
     * @var string
     */
    protected $primaryKey = 'fld_id';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['fld_id'];

    /**
     * @param $listing_source
     * @return mixed
     */
    public static function getStartDate($listing_source){

        $data = ZapCrawler::select('fld_id', 'fld_date', 'fld_satus', 'fld_source', 'fld_date_time')
            ->where('fld_source', '=', $listing_source)
            ->first();

        if(empty($data)){
            return self::createCrawler($listing_source);
        } else {
            return $data['fld_date'];
        }
    }

    /**
     * @param $listing_source
     * @return int
     */
    public static function createCrawler($listing_source){
        $crawler = new ZapCrawler();
        $crawler->fld_date = $rand = rand(91928291,929328392);
        $crawler->fld_satus =  "1";
        $crawler->fld_source = $listing_source;
        $crawler->fld_date_time = date("Y-m-d H:i:s");
        $crawler->save();
        return $rand;
    }


}
