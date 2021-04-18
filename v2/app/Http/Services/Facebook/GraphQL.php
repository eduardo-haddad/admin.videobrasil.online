<?php

namespace App\Http\Services\Facebook;

use App;
use Facebook\Facebook;

abstract class GraphQL
{
    /**
     * @var $fb \Facebook\Facebook
     */
    protected $fb;

    /**
     * @var $log Monolog\Logger
     */
    protected $log;

    /**
     *
     */
    public function __construct()
    {
        $this->fb = new Facebook([
            'app_id' => env('FACEBOOK_APP_ID'),
            'app_secret' => env('FACEBOOK_APP_SECRET'),
            'default_graph_version' => 'v3.2',
            'default_access_token' => env('FACEBOOK_ACCESS_TOKEN'),
        ]);
    }
}
