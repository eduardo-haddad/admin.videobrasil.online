<?php

namespace App\Observers;

use DB;
use App\Listing\Listing;

class ListingObserver
{
    /**
     * Listen to the Listing updated event.
     *
     * @param  \App\Listing\Listing  $user
     * @return void
     */
    public function updated(Listing $listing)
    {
        DB::table('ai_prod.serp_slot1')
          ->where('listing_id', $listing->listing_id)
          ->update(['searchsource_onoff' => $listing->getOriginal('searchsource_onoff')]);

        DB::table('ai_prod.serp_slot2')
          ->where('listing_id', $listing->listing_id)
          ->update(['searchsource_onoff' => $listing->getOriginal('searchsource_onoff')]);
    }
}
