<?php

namespace App\Http\ViewComposers;

use Auth;
use App\User;
use Illuminate\View\View;


class GlobalComposer
{
    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $cookie = request()->cookie('collapse');
        $view->with('collapse', json_decode($cookie));
    }
}
