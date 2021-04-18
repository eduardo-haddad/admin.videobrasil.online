<?php

namespace App\XmlIntegration;

use Illuminate\Database\Eloquent\Model;
use SystemDatabaseManager;

class SourceStatus extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'res_source_status';

    protected $connection = 'db_res_utf8';

    /**
     * The primary key column.
     *
     * @var string
     */
    protected $primaryKey = 'id';

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
    protected $guarded = ['id'];

    public static function getStatus($listing_source){
        $status = SourceStatus::select('searchsource_onoff', 'statstrends_onoff')
            ->where('source_name', '=', $listing_source)
            ->first();

        $search_source = empty($status['searchsource_onoff']) ? "1" : $status['searchsource_onoff'];
        $stats_trends = empty($status['statstrends_onoff']) ? "1" : $status['statstrends_onoff'];

        //dd($search_source);

        return compact('search_source', 'stats_trends');
    }


    public static function insertSource($listing_source, $search, $stat, $time, $now){
        $status = SourceStatus::where('source_name', '=', $listing_source)->first();

        if(empty($status)){
            $new_status = new SourceStatus();
            $new_status->source_name = $listing_source;
            $new_status->searchsource_onoff = $search;
            $new_status->statstrends_onoff = $stat;
            $new_status->timestamp = $time;
            $new_status->source_date = $now;
            return $new_status->save();
        }
        return false;
    }


}
