<?php

namespace App\XmlIntegration;

use App\XmlIntegration\XmlListing as Listing;
use Illuminate\Database\Eloquent\Model;

/**
 * Class XmlListing
 * Redundant model (check App\Listing\Listing) for compatibility with the Laravel Batch library (https://github.com/mavinoo/laravelBatch)
 */

class XmlListing extends Model
{
    /**
    * The table associated with the model.
    *
    * @var string
    */
    protected $table = 'res_listings';

    protected $connection = 'db_res_utf8';
    
    /**
    * The primary key column.
    *
    * @var string
    */
    protected $primaryKey = 'listing_id';
    
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
    protected $guarded = ['listing_id'];
    
    /**
    * The attributes that should be mutated to dates.
    *
    * @var array
    */
    protected $dates = [
        'auto_desc_created_at'
    ];
    
    /**
    * The attributes that should be cast to native types.
    *
    * @var array
    */
    protected $casts = [
        // 'listing_status' => 'boolean',
        // 'searchsource_onoff' => 'boolean',
        'auto_desc_ids' => 'array'
    ];

    /**
     * @param $listing_ids
     * @param $user_id
     * @param $current_time
     * @param $log_data
     * @return mixed
     */
    public static function unflagListings($listing_ids, $user_id, $current_time, $log_data){

        try {
            return Listing::where([
                ['listing_user_id', '=', $user_id],
                ['listing_status', '!=', '5']
            ])->whereNotIn('listing_zapid', $listing_ids)
                ->update([
                    'listing_unflagged_date' => $current_time,
                    'listing_status' => '5',
                    'listing_crawler_status' => '5',
                    'listing_proper_status' => '5',
                    'feature_status' => '0',
                    'feature_date' => '',
                    'feature_expiry' => '',
                    'listing_reactivate' => '0',
                ]);
        } catch (\Exception $e) { logWithOutput($log_data, "$e"); }

    }

}
 