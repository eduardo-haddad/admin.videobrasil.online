<?php

namespace App\XmlIntegration;

use App\Listing\Listing;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'res_featured_payment_details';

    protected $connection = 'db_res_utf8';

    /**
     * The primary key column.
     *
     * @var string
     */
    protected $primaryKey = 'payment_id';

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
    protected $guarded = ['payment_id'];

    public static function sourceOff($listing_user_id, $listing_source){
        // Set source off
        Listing::where('listing_user_id', '=', $listing_user_id)
            ->update([
                'searchsource_onoff' => '0',
                'statstrends_onoff' => '0'
            ]);

        SourceStatus::where('source_name', '=', $listing_source)
            ->update([
                'searchsource_onoff' => '0'
            ]);

        echo "User plan expired\n\n";
        return true;
    }


}
