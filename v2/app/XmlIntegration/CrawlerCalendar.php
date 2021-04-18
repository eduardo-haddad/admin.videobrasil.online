<?php

namespace App\XmlIntegration;

use Illuminate\Database\Eloquent\Model;
use SystemDatabaseManager;

class CrawlerCalendar extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'res_crawler_calendar';

    protected $connection = 'db_res_utf8';

    /**
     * The primary key column.
     *
     * @var string
     */
    protected $primaryKey = 'calendar_id';

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
    protected $guarded = ['calendar_id'];

    public static function insertTimestamp($listing_source, $timestamp, $now, $insertfilename = "", $filetype = "0", $total_scrapes){

        return CrawlerCalendar::create([
            'listing_source' => $listing_source,
            'start_time' => $now,
            'crawling_calendar_status' => "2",
            'xml_file_name' => $insertfilename,
            'filetype' => $filetype,
            'timestamp' => $timestamp,
            'total_scrapes' => $total_scrapes
        ]);

    }

}
