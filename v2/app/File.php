<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class File extends Model
{
    use LogsActivity;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * The attributes that need to be logged.
     */
    protected static $logAttributes = ['*'];

    /**
     * Set to true to logged only attributes
     * that were actually changed after the update.
     */
    protected static $logOnlyDirty = true;
}
