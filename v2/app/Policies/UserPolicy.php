<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * This method will be executed before any other methods on the policy.
     */
    public function before(User $user, $ability)
    {
        if($user->isSuperAdmin() && $ability != 'owns'){
            return true;
        }
    }

    /**
     *  Returns if the authenticated user can manage other users.
     */
    public function manage(User $user)
    {
        return false;
    }

    /**
     * Returns if the authenticated user owns the profile
     */
    public function owns(User $user, User $profile)
    {
        return $user->id == $profile->id;
    }
}
