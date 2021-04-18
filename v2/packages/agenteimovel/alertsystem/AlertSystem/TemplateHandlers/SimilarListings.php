<?php
require_once(__DIR__.'/../TemplateHandler.php');

class SimilarListings extends TemplateHandler {

    // Local Testing command: php /grinder/Cron/cron_similar_alerts_salerent_proc.php

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

    protected function getSimilarListingsRawData()
    {
        return $this->senderConfig['similarListingsRawData'];
    }

    protected function getSeeMoreLink()
    {
        return $this->senderConfig['seeMoreLink'];
    }

    protected function getSenderDomain()
    {
        return $this->senderConfig['senderDomain'];
    }

    protected function getSubstitutions()
    {
        $substitutions = [
            "search" => $this->getSearchParameters(),
            "see_more_link" => $this->getSeeMoreLink(),
            "sender_domain" => $this->getSenderDomain(),
            "listings" => [],
        ];

        $similarListingsRaw = $this->getSimilarListingsRawData();
        $tag = "?utm_source=agenteimovel&utm_medium=emailalert&utm_campaign=similar-listings";

        $max = 3;
        $count = 1;
        foreach($similarListingsRaw as $similarListing){
            if(isset($similarListing['listing_id']) && $count <= $max){
                $substitutions['listings'][] = $this->getListingSubstitutionData($similarListing, $tag);
                $count++;
            }
        }

        return $substitutions;
    }
}
