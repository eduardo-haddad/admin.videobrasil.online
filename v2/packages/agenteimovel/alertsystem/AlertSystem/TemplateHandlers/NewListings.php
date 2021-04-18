<?php
require_once(__DIR__.'/../TemplateHandler.php');

class NewListings extends TemplateHandler {

    // Local Testing command: php /grinder/Cron/new_listing_alert.php

    protected function isTransactional()
    {
        return false;
    }

    protected function isAbTest()
    {
        return false;
    }

    protected function getTemplateId()
    {
        return $this->senderConfig['templateId'];
    }

    protected function getSeeMoreLink()
    {
        return $this->senderConfig['seeMoreLink'];
    }

    protected function getNewListingsRawData()
    {
        return $this->senderConfig['newListingsRawData'];
    }

    protected function getSearchParameters()
    {
        $searchParamsRaw = $this->senderConfig['searchParameters'];
        $textualParams = [];
        if(isset($searchParamsRaw['header'])){
            $textualParams[] = $searchParamsRaw['header'];
        }
        return $textualParams;
    }

    protected function getSubstitutions()
    {
        $substitutions = [
            "search" => $this->getSearchParameters(),
            "see_more_link" => $this->getSeeMoreLink(),
            "listings" => [],
        ];

        $newListingsRaw = $this->getNewListingsRawData();
        $tag = "?utm_source=agenteimovel&utm_medium=emailalert&utm_campaign=new-listings";

        $max = 3;
        $count = 1;
        foreach($newListingsRaw as $newListing){
            if(isset($newListing['listing_id']) && $count <= $max){
                $substitutions['listings'][] = $this->getListingSubstitutionData($newListing, $tag);
                $count++;
            }
        }

        return $substitutions;
    }
}
