<?php

namespace App\XmlIntegration;

use Illuminate\Database\Eloquent\Model;

class PriceChange extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'res_listings_price_change';

    protected $connection = 'db_res_utf8';

    /**
     * The primary key column.
     *
     * @var string
     */
    protected $primaryKey = 'price_change_id';

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
    protected $guarded = ['price_change_id'];


}
