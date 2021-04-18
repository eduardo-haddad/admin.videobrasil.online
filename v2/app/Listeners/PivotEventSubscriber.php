<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Auth;

class PivotEventSubscriber
{
    /**
     * Log activity when attaching pivot.
     *
     * @param \App\Events\PivotAttached $event
     */
    public function onPivotAttached($event)
    {
        activity()->performedOn($event->pivot_b)
                  ->causedBy(Auth::user())
                  ->withProperties([
                      'to' => [
                        'subject_id' => $event->pivot_a->id,
                        'subject_type' => get_class($event->pivot_a)
                        ]
                    ])
                  ->log('attached');
    }

    /**
     * Log activity when detaching pivot.
     *
     * @param \App\Events\PivotDetached $event
     */
    public function onPivotDetached($event)
    {
      activity()->performedOn($event->pivot_b)
                ->causedBy(Auth::user())
                ->withProperties([
                    'from' => [
                      'subject_id' => $event->pivot_a->id,
                      'subject_type' => get_class($event->pivot_a)
                      ]
                  ])
                ->log('detached');
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param  \Illuminate\Events\Dispatcher $events
     */
    public function subscribe($events)
    {
        $events->listen(
            'App\Events\PivotAttached',
            'App\Listeners\PivotEventSubscriber@onPivotAttached'
        );

        $events->listen(
            'App\Events\PivotDetached',
            'App\Listeners\PivotEventSubscriber@onPivotDetached'
        );
    }
}
