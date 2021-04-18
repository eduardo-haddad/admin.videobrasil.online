<?php

namespace App\XmlIntegration;

use Illuminate\Database\Eloquent\Model;

class XmlKeyword extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'agenteimovel.xmls_keywords';

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
    public $timestamps = true;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    // Many to many relationship with Listings
    public function listings()
    {
        return $this->belongsToMany('App\Listing\Listing', 'agenteimovel.xmls_listings_keywords', 'keyword_id', 'listing_id');
    }


}
