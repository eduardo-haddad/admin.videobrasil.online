<?php
require_once(__DIR__.'/../TemplateHandler.php');

class PriceDecrease extends TemplateHandler {

    // Local Testing command: php Cron/price_decrease_alert.php

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

    protected function getListingsRawData()
    {
        return $this->senderConfig['listingsRawData'];
    }

    protected function getSenderDomain()
    {
        return $this->senderConfig['senderDomain'];
    }

    protected function getListingSubstitutionData($listing, $utmTag = '')
    {
        $data = parent::getListingSubstitutionData($listing, $utmTag);
        $data['price_decrease'] = $this->formatPrice($listing['price_decrease']);
        return $data;
    }

    protected function getSubstitutions()
    {
        $substitutions = [
            "search" => $this->getSearchParameters(),
            "sender_domain" => $this->getSenderDomain(),
            "listings" => [],
        ];

        $listingsRaw = $this->getListingsRawData();
        $tag = "?utm_source=agenteimovel&utm_medium=emailalert&utm_campaign=price-decrease";
        if(isset($listingsRaw['listing_id'])){
            $substitutions['listings'][] = $this->getListingSubstitutionData($listingsRaw, $tag);
        }

        return $substitutions;
    }
}
