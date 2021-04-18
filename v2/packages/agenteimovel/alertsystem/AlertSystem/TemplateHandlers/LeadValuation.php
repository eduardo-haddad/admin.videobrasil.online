<?php

require_once(__DIR__.'/../TemplateHandler.php');

class LeadValuation extends TemplateHandler {

    // Local Testing command: php 

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
        return 'evaluation-2';
    }

    protected function getSenderSubdomain()
    {
        $senderSubdomain = $this->projectConfig['emailSubdomain_leads_default'];

        return $senderSubdomain;
    }

    private function getMapurl()
    {
        return $this->senderConfig['mapurl'];
    }

    private function getGooglekey()
    {
        return $this->senderConfig['googlekey'];
    }

    private function getUrlstring()
    {
        return $this->senderConfig['urlstring'];
    }

    private function getPopupurlstring()
    {
        return $this->senderConfig['popupurlstring'];
    }

    private function getDisplayaddress()
    {
        return $this->senderConfig['displayaddress'];
    }

    private function getPrice()
    {
        return $this->formatPrice($this->senderConfig['price']);
    }
    
    private function getPricesqm()
    {
        return $this->formatPrice($this->senderConfig['pricesqm']);
    }

    private function getPricealugar()
    {
        return $this->formatPrice($this->senderConfig['pricealugar']);
    }

    private function getPricesqmalugar()
    {
        return $this->formatPrice($this->senderConfig['pricesqmalugar']);
    }

    private function getUnsubscribelink()
    {
        return $this->senderConfig['unsubscribelink'];
    }

    private function getUnsubscribelinkglobal()
    {
        return $this->senderConfig['unsubscribelinkglobal'];
    }

    private function getNameFrom()
    {
        return $this->senderConfig['nameFrom'];
    }

    private function getSubject()
    {
        return $this->senderConfig['subject'];
    }

    private function getEmailType()
    {
        return $this->senderConfig['email_type'];
    }

    private function getBedroom()
    {
        return $this->senderConfig['bedroom'];
    }

    private function getNeighStats()
    {
        return $this->senderConfig['neighStats'];
    }

    private function getNeighName()
    {
        return $this->senderConfig['neighName'];
    }

    private function getSerp()
    {
        return $this->senderConfig['serp'];
    }

    protected function getSubstitutions ()
    {
        $substitutions = [  
            // 'mapurl'                => $this->getMapurl(),
            // 'googlekey'             => $this->getGooglekey(),
            'urlstring'             => $this->getUrlstring(),
            'popupurlstring'        => $this->getPopupurlstring(),
            'displayaddress'        => $this->getDisplayaddress(),
            'price'                 => $this->getPrice(),
            'pricesqm'              => $this->getPricesqm(),
            'pricealugar'           => $this->getPricealugar(),
            'pricesqmalugar'        => $this->getPricesqmalugar(),
            'unsubscribelink'       => $this->getUnsubscribelink(),
            'unsubscribelinkglobal' => $this->getUnsubscribelinkglobal(),
            'nameFrom'              => $this->getnameFrom(),
            'subject'               => $this->getSubject(),
            'email_type'            => $this->getEmailType(),
            'bedroom'               => $this->getBedroom(),
            'neighStats'            => $this->getNeighStats(),
            'neighName'             => $this->getNeighName(),
            'serp'                  => $this->getSerp()
        ];

        return $substitutions;
    }
}
