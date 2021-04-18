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
        return false;
    }

    protected function getTemplateId()
    {
        $templateId = 'hotLead';

        return $templateId;
    }

    protected function getSenderSubdomain()
    {
        $senderSubdomain = $this->projectConfig['emailSubdomain_alerts'];

        return $senderSubdomain;
    }

    protected function getSubstitutions()
    {
        $substitutions =  $this->projectConfig;

        return $substitutions;
    }
}