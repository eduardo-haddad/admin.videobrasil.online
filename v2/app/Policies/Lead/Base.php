<?php

namespace App\Policies\Lead;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class Base
{
    use HandlesAuthorization;

    /**
     * This method will be executed before any other methods on the policy.
     */
    public function before(User $user, $ability)
    {
        if($user->isSuperAdmin() && $ability != 'claim'){
            return true;
        }
    }

    /**
     *  Returns if the authenticated user can manage leads.
     */
    public function manage(User $user)
    {
        return $user->hasRole('lead-manager');
    }
}
