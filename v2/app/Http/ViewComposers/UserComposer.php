<?php

namespace App\Http\ViewComposers;

use Auth;
use App\User;
use Illuminate\View\View;


class UserComposer
{
    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        if(Auth::check()){
            $view->with('user', Auth::user());
        }
    }
}
