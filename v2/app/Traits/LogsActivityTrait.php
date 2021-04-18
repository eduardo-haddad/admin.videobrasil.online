<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity as LogsActivityBase;
use Spatie\Activitylog\ActivityLogger;

trait LogsActivityTrait
{
    use LogsActivityBase;

    /**
     * @var array
     */
    protected $e_properties;

    /**
     * @param array $value
     */
    public function setExtraLogProperties($value)
    {
        $this->e_properties = $value;
    }

    /**
     * @return array
     */
    public function getExtraLogProperties()
    {
        return $this->e_properties;
    }

    /**
     * Overwritten from the original LogsActivity trait
     * in order to allow extra properties when loggin model events.
     */
    protected static function bootLogsActivity()
    {
        static::eventsToBeRecorded()->each(function ($eventName) {
            return static::$eventName(function (Model $model) use ($eventName) {
                if (! $model->shouldLogEvent($eventName)) {
                    return;
                }

                $description = $model->getDescriptionForEvent($eventName);

                $logName = $model->getLogNameToUse($eventName);

                if ($description == '') {
                    return;
                }

                $props = $model->attributeValuesToBeLogged($eventName);

                if($e_props = $model->getExtraLogProperties()){
                    $props = array_merge($props, $e_props);
                }

                app(ActivityLogger::class)
                    ->useLog($logName)
                    ->performedOn($model)
                    ->withProperties($props)
                    ->log($description);
            });
        });
    }
}
