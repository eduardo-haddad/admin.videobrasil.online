<?php

class AlertSystemConfig {
    static public function getConfig()
    {
        return [
            // SparkPost Key
            "sparkpostApiKey" => '9ecf8d5895d5900e3fb8100aaa06be944542896d',
            "sparkpostApiKey_test" => '9ecf8d5895d5900e3fb8100aaa06be944542896d',

            // SparkPost Templates
            "emailTemplate_newlistings" => 'new-listings',
            "emailTemplate_newlistings_lancamento" => 'new-listings-lancamento',

            "emailTemplate_similar" => 'similar-listings',
            "emailTemplate_similar_lancamentos" => 'similiar-listings-lancamento',

            "emailTemplate_pricedecrease" => 'price-decrease',
            "emailTemplate_pricedecrease_lancamentos" => 'price-decrease-lancamento',

            "emailTemplate_confirmation" => 'lead-confirmation',
            "emailTemplate_fb_confirmation" => 'fb-lead-confirmation',

            // Sender emails subdomains (no-reply@{subdomain}.agenteimovel.com.br) - See configuration at SparkPost app
            // Lançamentos are usually not using the ones defines here, but statically on the SparkPost template
            "emailSubdomain_leads_default"      => 'confirmacao',
            "emailSubdomain_leads_lancamentos"  => 'l.confirmacao',
            "emailSubdomain_leads_alugar"       => 'a.confirmacao',
            "emailSubdomain_leads_comprar"      => 'r.confirmacao',

            "emailSubdomain_fbleads"            => 'fb-confirmacao',
            "emailSubdomain_alerts"             => 'alertas',

            "emailSubdomain_similar_lancamentos"  => 'l.similar',
            "emailSubdomain_similar_alugar"       => 'a.similar',
            "emailSubdomain_similar_comprar"      => 'r.similar',

            "emailSubdomain_newlistings_lancamentos"  => 'l.novos',
            "emailSubdomain_newlistings_alugar"       => 'a.novos',
            "emailSubdomain_newlistings_comprar"      => 'r.novos',

            "emailSubdomain_pricedecrease_alugar"      => 'a.preco',
            "emailSubdomain_pricedecrease_comprar"      => 'r.preco',
        ];
    }
}

