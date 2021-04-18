<?php

require_once(__DIR__.'/SendEmail.php');
require_once(__DIR__.'/AlertSystemConfig.php');
require_once(__DIR__.'/AlertSystemUtilityManager.php');

abstract class TemplateHandler {
    protected $projectConfig;
    protected $senderConfig;
    protected $recipientList = [];
 
    const TYPE_REVENDA = 1;
    const TYPE_ALUGUEL = 2;
    const TYPE_LANCAMENTO = 7;

    abstract protected function getTemplateId();
    abstract protected function getSubstitutions();
    abstract protected function isTransactional();
    abstract protected function isAbTest();

    // Local Testing command: php /grinder/Cron/cron_similar_alerts_salerent_proc.php
    
    public function __construct($senderConfig)
    {
        $this->senderConfig = $senderConfig;
        $this->projectConfig = AlertSystemConfig::getConfig();
    }

    protected function getMediaUrl()
    {
        return FIX_IMAGE_CDN_URL;
    }

    protected function getPermalink($listing)
    {
        return AlertSystemUtilityManager::getListingURL($listing);
    }

    protected function getSource()
    {
        return $this->senderConfig['source'];
    }

    protected function getListingType()
    {
        return $this->senderConfig['listingType']; 
    }

    protected function formatPrice($price)
    {
        if($price == null || empty($price)){
            return '';
        }
        return number_format($price,0,',','.');
    }

    protected function getRecipients()
    {
        return $this->recipientList;
    }

    protected function getSearchParameters()
    {
        $searchParamsRaw = $this->senderConfig['searchParameters'];
        $textualParams = [];

        foreach($searchParamsRaw as $param => $value){
            if($value == null){
                continue;
            }
            if(is_string($value) && empty($value)){
                continue;
            }
            if(is_numeric($value) && intval($value) == 0) {
                continue;
            }
            if(is_array($value)){
                if(empty($value['from']) || $value['from'] == 0){
                    continue;
                }
                if($param == 'price'){
                    $value['from'] = $this->formatPrice($value['from']);
                    $value['to'] = $this->formatPrice($value['to']);
                    $value = "de R$ ".$value['from']." até R$ ".$value['to'];
                } else {
                    $value = AlertSystemUtilityManager::getPropertyItemsValue($value['from'],$value['to']);
                }
            }
            switch($param){
                case 'type':
                    $textualParams[] = $value;
                    break;
                case 'bedrooms': 
                    $textualParams[] = $value.' dormitórios'; 
                    break;
                case 'bathroom': 
                    $textualParams[] = $value.' suítes';
                    break;
                case 'area': 
                    $textualParams[] = $value.' m²';
                    break;
                case 'parkingAreas':
                    $textualParams[] = $value.' vagas de garagem';
                    break;
                case 'price': 
                    $textualParams[] = 'Preço '.$value;
                    break;
                case 'location': 
                    $textualParams[] = $value;
                    break;
            }
        }
        return $textualParams;
    }

    protected function getListingSubstitutionData($listing, $utmTag = '')
    {
        return [
            'permalink' => $this->getPermalink($listing).$utmTag,
            'image' => $this->getMediaUrl().(empty($listing['listing_display_photo']) ? 'no_image.gif' : $listing['listing_display_photo']),
            'name' => $listing['listing_title'],
            'location' => implode(", ",[
                $listing['listing_district'],
                $listing['listing_city'],
                $listing['listing_state'],
            ]),
            'type' => $listing['listing_ptype_name'],
            'bedrooms' => $this->getValueOrRange($listing['listing_bedroom'],$listing['listing_bedroomfrom'],$listing['listing_bedroomto']),
            'ensuites' => $this->getValueOrRange($listing['listing_bathroom'],$listing['listing_suitefrom'],$listing['listing_suiteto']),
            'parkingSpaces' => $this->getValueOrRange($listing['listing_parking'],$listing['listing_parkingspacesfrom'],$listing['listing_parkingspacesto']),
            'area' => $this->getValueOrRange($listing['listing_sqmeter'],$listing['listing_areafrom'],$listing['listing_areato']),
            'squareMeterPrice' => $this->formatPrice(isset($listing['listing_pricepersqfrom']) ? $listing['listing_pricepersqfrom'] : $listing['listing_pricepersq']),
            'price' => $this->getValueOrRange(
                $this->formatPrice($listing['listing_price']),
                $this->formatPrice($listing['listing_pricefrom']),
                $this->formatPrice($listing['listing_priceto']),
                "Sob consulta"),
        ];
    }

    private function getValueOrRange($value, $rangeFrom, $rangeTo, $default = '')
    {
        if($value != null && !empty($value)){
            return $value;
        }
        if($rangeFrom != null && !empty($rangeFrom) && $rangeTo != null && !empty($rangeTo)){
            return AlertSystemUtilityManager::getPropertyItemsValue($rangeFrom, $rangeTo);
        }
        return $default;
    }

    public function addRecipient($name, $email, $unsubscriptionLink = '')
    {
        $recipient = [
            'address' => [
                'name' => $name,
                'email' => $email,
            ],
            'substitution_data' => [
                "unsubscription_link" => $unsubscriptionLink,
                "name" => $name,
            ]
        ];
        $this->recipientList[] = $recipient;
    }

    public function send($debug = false)
    {
        // Subject, sender email, html of template and reply-to are configured on SparkPost app
        // We only set the template id, substitutions, destination and subdomain of sender email (also a substitution)

        $key = 'sparkpostApiKey';
        $emailSender = new SendEmail($this->projectConfig[$key]);
        if($debug){
            $emailSender->setDebug(true); // this will not send but output the settings instead 
        }
        $emailSender->sendByTemplate(
            $this->getTemplateId(),
            $this->getSubstitutions(),
            $this->getRecipients(),
            $this->isTransactional(),
            $this->isAbTest()
        );
    }

    
}
