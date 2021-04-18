<?php

namespace App\Http\ViewComposers;

use App\Role;
use Illuminate\View\View;


class RoleComposer
{
    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $roles = Role::all();
        $roles = $roles->chunk($roles->count() / 2);
        $view->with('roles', $roles);
    }
}
