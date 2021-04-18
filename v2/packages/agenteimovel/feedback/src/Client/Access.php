<?php

namespace Feedback\Client;

use Carbon\Carbon;
use App\Traits\LogsActivityTrait;
use Illuminate\Database\Eloquent\Model;

class Access extends Model
{
    use LogsActivityTrait;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'client_accesses';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'expired_at',
    ];

    /**
     * The attributes that need to be logged.
     */
    protected static $logAttributes = ['*'];

    /**
     * Set to true to logged only attributes
     * that were actually changed after the update.
     */
    protected static $logOnlyDirty = true;

    /**
     * Get the user accessing the lead
     */
    public function user()
    {
        return $this->belongsTo('App\User', 'accesss_user_id');
    }
}
