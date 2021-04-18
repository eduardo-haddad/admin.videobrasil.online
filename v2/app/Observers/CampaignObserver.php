<?php

namespace App\Observers;

use App\Campaign;
use App\Lead\Lead;

class CampaignObserver
{
    /**
     * Listen to the Campaign saved event.
     *
     * @param  \App\Campaign  $user
     * @return void
     */
    public function saved(Campaign $campaign)
    {
        // Load the modal again due to default values set on database level
        $campaign = Campaign::find($campaign->id);
        $campaign->load('listings');

        // Note: We're updating each model at a time to Trigger ORM Events.
        //       When issuing a mass update, models are never retrieved.

        if($campaign->overall_status == 'active'){
            // If Campaign is active, publish all Listings attached.
            $campaign->listings->each(function($listing){
                $listing->update(['searchsource_onoff' => '1']);
            });

            // Search for Leads generated between the Campaign delivery time
            // and attached them to the Campaign.
            $listings = $campaign->listings->pluck('listing_id')->toArray();

            $leads = Lead::whereBetween('datetime', [$campaign->start_date, $campaign->end_date])
                         ->whereIn('listing_id', $listings)
                         ->whereNull('campaign_id');

            if($leads->exists()){
                $leads->update([
                    'campaign_id' => $campaign->id,
                    'lead_value' => $campaign->lead_price
                ]);
            }
        }

        if($campaign->unpublish_listings && ($campaign->overall_status == 'paused' || $campaign->overall_status == 'expired')){
            // If Campaign is paused or expired and is set to unpublish Listings, unpublish all Listings.
            $campaign->listings->each(function($listing){
                $listing->update(['searchsource_onoff' => '0']);
            });
        }
    }

    /**
     * Listen to the Campaign deleted event.
     *
     * @param  \App\Campaign  $user
     * @return void
     */
    public function deleted(Campaign $campaign)
    {
        // Note: We're updating each model at a time to Trigger ORM Events.
        //       When issuing a mass update, models are never retrieved.

        if($campaign->unpublish_listings){
            // If Campaign is deleted and is set to unpublish Listings, unpublish all listings.
            $campaign->listings->each(function($listing){
                $listing->update(['searchsource_onoff' => '0']);
            });
        }
    }
}
