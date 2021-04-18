<?php

namespace App;

use Storage;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Xml extends Model
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

    /**
     * Get the XML file last modified date.
     */
    public function getLastModifiedAttribute()
    {
        if($this->file && Storage::exists($this->file)){
            $timestamp = Storage::lastModified($this->file);
            return Carbon::createFromTimeStamp($timestamp);
        }

        return null;
    }
}
