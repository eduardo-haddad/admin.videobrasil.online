<?php
require_once(__DIR__.'/../TemplateHandler.php');

class LeadConfirmation extends TemplateHandler {

    // Local Testing URL: http://agenteimovel.localhost/Publish/Users/submitListing/agent_email.php?listing_id=6564420&toname=Even&toemail=&fromname=Ricardo%20Teste&hard_user_name=&your_broker_name_txt=&fromphone=(11)%20999999-999&listing_coming_source=&listing_coming_campaign=&fromemail=ricardo@agenteimovel.com.br&frommessage=Prezados%2C%3Cbr%3E%3Cbr%3EGostaria%20de%20receber%20mais%20informa%E7%F5es%20sobre%20este%20im%F3vel%2C%20que%20encontrei%20no%20Agente%20Im%F3vel.&alertsrec=1&sessuid=&thank=you&transaction_id=80945155&sid=0.09367311740079087 

    protected function isTransactional()
    {
        return true;
    }

    protected function isAbTest()
    {
        $template = $this->getTemplateId();
        if($template == 'abtests2-lead-confirmation-lancamentos'){
            return true;
        }
        return false;
    }

    protected function getTemplateId()
    {
        $templateId = 'lead-confirmation';

        if($this->getListingType() == self::TYPE_LANCAMENTO){
            $templateId = 'abtests2-lead-confirmation-lancamentos';
        }

        if($this->getSource() == 'leads-ad'){
            $templateId = 'lead-confirmation-facebook';
        }

        return $templateId;
    }

    protected function getSenderSubdomain()
    {
        $senderSubdomain = $this->projectConfig['emailSubdomain_leads_default'];
        // From Facebook
        if($this->getSource() == 'leads-ad'){
            $senderSubdomain = $this->projectConfig['emailSubdomain_fbleads'];
        // From site
        } else {
            switch($this->getListingType()){
                default:
                    $senderSubdomain = $this->projectConfig['emailSubdomain_leads_default'];
                    break;
                case self::TYPE_LANCAMENTO:
                    $senderSubdomain = $this->projectConfig['emailSubdomain_leads_lancamentos'];
                    break;
                case self::TYPE_ALUGUEL:
                    $senderSubdomain = $this->projectConfig['emailSubdomain_leads_alugar'];
                    break;
                case self::TYPE_REVENDA:
                    $senderSubdomain = $this->projectConfig['emailSubdomain_leads_comprar'];
                    break;
            }
        }
        return $senderSubdomain;
    }

    private function getRealState()
    {
        return $this->senderConfig['realState'];
    }

    private function getListingName()
    {
        return $this->senderConfig['listingName'];
    }

    private function getListingAddress()
    {
        return $this->senderConfig['listingAddress'];
    }

    protected function getSimilarListingsRawData()
    {
        return $this->senderConfig['similarListingsRawData'];
    }

    protected function getSubstitutions()
    {
        $substitutions = [
            "sender_subdomain" => $this->getSenderSubdomain(),
            "search" => $this->getSearchParameters(),
            "real_state" => $this->getRealState(),
            "listing_name" => $this->getListingName(),
            "listing_address" => $this->getListingAddress(),
            "listings" => [],
        ];

        $similarListingsRaw = $this->getSimilarListingsRawData();
        $tag = "?utm_source=agenteimovel&utm_medium=remarketing-email&utm_campaign=remarketing-similar-listing";

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
