<?php

namespace App\Http\Services\Facebook\Controllers;

use FacebookAds\Object\Page as PageFB;
use FacebookAds\Object\Fields\PageFields;

class Page extends \App\Http\Services\Facebook\SDK
{

    public function __construct() {
        parent::__construct();
        $this->page = new PageFB('277823286593');
    }

    public function getForms() {
        return $this->page->getLeadgenForms([], [])->getObjects();
    }
}

?>
