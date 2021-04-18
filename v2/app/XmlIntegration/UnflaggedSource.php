<?php

namespace App\XmlIntegration;

use Illuminate\Database\Eloquent\Model;
use SystemDatabaseManager;

class UnflaggedSource extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'res_handling_unflagged_source';

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

    public static function refreshUnflaggedSource($listing_source, $now, $type = 0)
    {
        UnflaggedSource::where('listing_source', '=', $listing_source)->delete();

        $u_source = new UnflaggedSource();
        $u_source->listing_source = $listing_source;
        $u_source->type = $type;
        $u_source->source_date = $now;
        return $u_source->save();
    }


}
