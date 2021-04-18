<?php

namespace App\XmlIntegration;

use Illuminate\Database\Eloquent\Model;

/**
 * Class XmlNewconst
 * Redundant model (check App\Listing\Newconst) for compatibility with the Laravel Batch library (https://github.com/mavinoo/laravelBatch)
 */

class XmlNewconst extends Model
{
    use \Awobaz\Compoships\Compoships;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'res_listings_newconst';

    protected $connection = 'ai_prod';


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
    protected $guarded = [];

    /**
     *
     */
    public function listing()
    {
        return $this->belongsTo('App\Listing\Listing', 'listing_id');
    }


}
