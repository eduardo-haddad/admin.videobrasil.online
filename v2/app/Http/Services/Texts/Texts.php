<?php
namespace App\Http\Services\Texts;

use Storage;

class Texts
{
    public function get($lead, $type, $lead_type = null) {
        $path = 'config/';

        if($type == 'whatsapp') {
            if($lead_type == 'leads.pre.qa') {
                $lead_type = 'quali';
            } else if ($lead_type == 'leads.pre.sale') {
                $lead_type = 'sale';
            } else {
                $lead_type = 'pos';
            }

            $path .= $type.'/'.$lead_type.'-text.txt';

        }else {
            $path .= 'pre/'.$type.'-'.$lead_type.'-text.txt';
        }

        $file = \Storage::disk('public')->has($path);

        if($file) {
            $file = \Storage::disk('public')->get($path);
        }else{
            return false;
        }

        if(!isset($lead->listing)) return false;

        $maps = Self::replaceTextTags($lead);

        foreach ($maps as $key => $map) {
            $file = str_replace($key, $map, $file);
        }

        return $file;
    }

    static private function replaceTextTags($lead){
        $operator = \Auth::user() ? \Auth::user()->name : 'sistema';
        $maps = [
            '@operador'         => getGender('operator', $operator),
            '@operador_nome'    => $operator,
            '@lead_id'          => $lead->lead_id,
            '@lead_nome'        => $lead->fromname,
            '@lead_telefone'    => $lead->fromphone1,
            '@empre_id'         => $lead->listing_id,
            '@empre_nome'       => $lead->listing && $lead->listing->newconst ? ucwords(strtolower(mb_convert_case($lead->listing->newconst->listing_title, MB_CASE_TITLE))) : '',
            '@empre_preco_de'   => $lead->listing && $lead->listing->newconst ? formatNumber($lead->listing->newconst->listing_pricefrom, false, true) : $lead->listing->listing_price,
            '@empre_preco_ate'  => $lead->listing && $lead->listing->newconst ? $lead->listing->newconst->listing_priceto : $lead->listing->listing_price,
            '@empre_dorm_de'    => $lead->listing && $lead->listing->newconst ? $lead->listing->newconst->listing_bedroomfrom : '',
            '@empre_dorm_ate'   => $lead->listing && $lead->listing->newconst ? $lead->listing->newconst->listing_bedroomto : '',
            '@empre_area_de'    => $lead->listing && $lead->listing->newconst ? $lead->listing->newconst->listing_areafrom : '',
            '@empre_area_ate'   => $lead->listing && $lead->listing->newconst ? $lead->listing->newconst->listing_areato : '',
            '@empre_vagas_de'   => $lead->listing && $lead->listing->newconst ? $lead->listing->newconst->listing_parkingfrom : '',
            '@empre_vagas_ate'  => $lead->listing && $lead->listing->newconst ? $lead->listing->newconst->listing_parkingto : '',
            '@empre_banhe_de'   => $lead->listing && $lead->listing->newconst ? $lead->listing->newconst->listing_bathroomfrom : '',
            '@empre_banhe_ate'  => $lead->listing && $lead->listing->newconst ? $lead->listing->newconst->listing_bathroomto : '',
            '@empre_link'       => $lead->listing ? env('PORTAL_URL').propertyUrl($lead->listing) : '',
            '@empre_zona'       => $lead->listing && $lead->listing->district ? $lead->listing->district->ZONA : '',
            '@periodo'          => dayPeriod(),
            '@empre_rua'        => $lead->listing ? $lead->listing->listing_stname : '',
            '@empre_bairro'     => $lead->listing ? $lead->listing->listing_district : '',
            '@empre_cidade'     => $lead->listing ? $lead->listing->listing_city : '',
            '@empre_estado'     => $lead->listing ? $lead->listing->listing_state : '',
            '@ajudar'           => getGender('lead', $lead->fromname)
        ];

        return $maps;
    }
}
?>