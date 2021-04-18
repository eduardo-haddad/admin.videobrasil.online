<?php

namespace App\Listeners;

use App\Events\CallbackSchedule;
use Thomasjohnkane\Snooze\Models\ScheduledNotification;

class CallbackNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  CallbackSchedule  $event
     * @return void
     */
    public function handle(CallbackSchedule $event)
    {
        $notification = ScheduledNotification::where('data', 'like', '%"qa_id": '.$event->qa->id.'%')->get();

        if($notification->isEmpty()){
            ScheduledNotification::create([
                'user_id' => \Auth::id(),
                'send_at' => $event->qa->callback_scheduled_at,
                'type'    => 'App\Notifications\Callback',
                'data'    => ['qa_id' => $event->qa->id]
            ]);
        }

        foreach($notification as $notification) {
            if($notification && $event->qa->callback_scheduled_at) {
                $notification->sent = 0;
                $notification->save();
                $notification->reschedule($event->qa->callback_scheduled_at);
            }else{
                $notification->send_at = null;
                $notification->sent = 0;
                $notification->save();
            }
        }
        
    }
}