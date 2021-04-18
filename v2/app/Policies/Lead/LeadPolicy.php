<?php

namespace App\Policies\Lead;

use App\Lead\Lead;

class LeadPolicy extends Base
{
    /**
     * Returns if the authenticated user can claim the lead.
     */
    public function claim(\App\User $user, Lead $lead)
    {
        return !$lead->qa || !$lead->qa->agent;
    }

    /**
     * Returns if the authenticated user can give/see feedback.
     */
    public function feedback(\App\User $user)
    {
        if($user->hasRole('lead-manager')){
            return true;
        }

        if($user->hasRole('broker-lp')){
            $lead = Lead::find(app('request')->route('lead'));
            return $user->clients()->where('user_id', $lead->listing_user_id)->exists();
        }
    }
}
