<?php

use Illuminate\Support\Facades\Log;

if (!function_exists('_route')) {
    /**
     * When Laravel is installed on a subdirectory and You're using domain routes, the route() helper returns
     * a URL missing that same subdirectory. The url() helper keeps returning the correct URL.
     * To solve that issue, we use the route() helper to return the relative URL and concatenates
     * that URI with the root URL.
     *
     * @param string $name
     * @param array $parameters
     * @return string
     */
    function _route($name, $parameters = [])
    {
        return url('/')  . route($name, $parameters, false);
    }
}

if (!function_exists('portal_route')) {
    /**
     * @param string $path
     */
    function portal_route($path)
    {
        return url(env('PORTAL_URL') . '/' . trim($path, '/'));
    }
}

if (!function_exists('feedback_route')) {
    /**
     * @param string $path
     */
    function feedback_route($name, $parameters = [])
    {
        return url(env('FEEDBACK_URL')  . route('feedback.' . $name, $parameters, false));
    }
}

if (!function_exists('logWithOutput')) {

    function logWithOutput($log_data, $message, $message_pt = '', $level = "error", $output = true)
    {
        $log = $log_data['logger'];
        $data = $log_data['data'];

        if(!empty($message_pt)){
            $message .= " / $message_pt";
        }

        switch($level){
            case "error":
                $log->error($message, $data); break;
            case "warning":
                $log->warning($message, $data); break;
            case "info":
                $log->info($message, $data); break;
        }

        if($output){
            echo "\n".strtoupper($level).": $message\n\n";
        }

    }
}

if (!function_exists('curl_get_file_contents')) {

    function curl_get_file_contents($url){
        $c = curl_init();
        curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($c, CURLOPT_URL, $url);
        $contents = curl_exec($c);
        curl_close($c);

        if ($contents)
            return $contents;
        else
            return false;
    }
}

if (!function_exists('fileExists')) {
    /**
     * @param string $path
     */
    function fileExists($url) {

        $url = filter_var($url, FILTER_VALIDATE_URL);

        $handle = curl_init($url);

        curl_setopt_array($handle, array(
            CURLOPT_FOLLOWLOCATION => TRUE,
            CURLOPT_NOBODY => TRUE,
            CURLOPT_HEADER => FALSE,
            CURLOPT_RETURNTRANSFER => FALSE,
            CURLOPT_SSL_VERIFYHOST => FALSE,
            CURLOPT_SSL_VERIFYPEER => FALSE
        ));

        $response = curl_exec($handle);
        $httpCode = curl_getinfo($handle, CURLINFO_EFFECTIVE_URL);
        $httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);

        curl_close($handle);

        return $httpCode;
    }
}

if (!function_exists('strip_accents')) {
    function strip_accents(string $string): string
    {
        $toDeal = [
            'a' => "/(á|à|ã|â|ä)/",
            'A' => "/(Á|À|Ã|Â|Ä)/",
            'e' => "/(é|è|ê|ë)/",
            'E' => "/(É|È|Ê|Ë)/",
            'i' => "/(í|ì|î|ï)/",
            'I' => "/(Í|Ì|Î|Ï)/",
            'o' => "/(ó|ò|õ|ô|ö)/",
            'O' => "/(Ó|Ò|Õ|Ô|Ö)/",
            'u' => "/(ú|ù|û|ü)/",
            'U' => "/(Ú|Ù|Û|Ü)/",
            'n' => "/(ñ)/",
            'N' => "/(Ñ)/"
        ];

        return preg_replace(array_values($toDeal), array_keys($toDeal), $string);
    }
}

if (!function_exists('strip_special_chars')) {
    function strip_special_chars(string $string): string
    {
        $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens
        return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars
    }
}

if (!function_exists('propertyUrl')) {
    function propertyUrl($property) {
        if(!empty($property)) {
            $url = '/';
            $url .= in_array($property->listing_type, ['lancamentos', 'lançamentos', 'Lancamentos', 'Lançamentos']) ? 'lancamentos/' : '';
            $url .= $property->listing_type == 'Comprar' ? 'imoveis/a-venda/' : ($property->listing_type == 'Alugar' ? 'imoveis/alugar/' : '');
            $url .= $property->listing_state.'/';
            $url .= $property->listing_city.'/';
            $url .= $property->listing_district.'/';
            $url .= !empty($property->listing_stname) ? $property->listing_stname.'/' : '';
            $url .= 'd/'.$property->listing_id.'/';
            
            $url = strip_accents($url);
            $url = explode('/', $url);
            $url = preg_replace("/[^a-zA-Z- 0-9]+/", "", $url);
            $url = str_replace(' ', '-', $url);
            $url = implode('/', $url);
            
            return strtolower($url);
        }
    }
}

if (!function_exists('toDecimal')) {
    function toDecimal(string $string)
    {
        $value = preg_replace('/[^0-9_ %\[\]\(\)%&-]/s', '', $string);
        return number_format($value, 2, '.', '');
    }
}

if (!function_exists('trackingSku')) {
    function trackingSku($listing) {

        $sku['category'] = [
            'name'      => $listing->client->user_ptype,
            'type_1'    => ($listing->client->user_ptype == '1' ? 'PPL' : 'Plano').' - Mensagem',
            'type_2'    => ($listing->client->user_ptype == '1' ? 'PPL' : 'Plano').' - Chat',
            'type_3'    => ($listing->client->user_ptype == '1' ? 'PPL' : 'Plano').' - Tel1',
        ];

        if($listing->client->user_ptype == "1"){
            // lanca
            if(in_array($listing->listing_type, ['lancamentos', 'lançamentos', 'Lancamentos', 'Lançamentos'])){
                $sku['sku'] = [
                    'type_1'    =>  "LANPPLMENSU"."_".$listing->client->user_name."_".$listing->newconst->listing_title."-".$listing->client->user_id,
                ];
            }
          
            // comprar
            if($listing->listing_type == "comprar" || $listing->listing_type == "Comprar"){
                $sku['sku'] = [
                    'type_1'    =>  "REVPPLMENSU"."_".$listing->client->user_name."-".$listing->client->user_id,
                ];
            }
          
            // alugar
            if($listing->listing_type == "alugar" || $listing->listing_type == "Alugar"){
              $sku['sku'] = [
                    'type_1'    =>  "ALUPPLMENSU"."_".$listing->client->user_name."-".$listing->client->user_id,
                ];
            }
        }
        //No PPL
        else{
            // lanca
            if(in_array($listing->listing_type, ['lancamentos', 'lançamentos', 'Lancamentos', 'Lançamentos'])){
              $sku['sku'] = [
                    'type_1'    =>  "LANPLAMENSU"."_".$listing->client->user_name."_".$listing->newconst->listing_title."-".$listing->client->user_id,
                ];
            }
          
            // comprar
            if($listing->listing_type == "comprar" || $listing->listing_type == "Comprar"){
              $sku['sku'] = [
                    'type_1'    =>  "REVPLAMENSU"."_".$listing->client->user_name."-".$listing->client->user_id,
                ];
            }
          
            // alugar
            if($listing->listing_type == "alugar" || $listing->listing_type == "Alugar"){
              $sku['sku'] = [
                    'type_1'    =>  "ALUPLAMENSU"."_".$listing->client->user_name."-".$listing->client->user_id,
                ];
            }
        }
        
        return $sku;
    }
}

if (!function_exists('UUIDv4')) {
    function UUIDv4() {
        return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',

        // 32 bits for "time_low"
        mt_rand(0, 0xffff), mt_rand(0, 0xffff),

        // 16 bits for "time_mid"
        mt_rand(0, 0xffff),

        // 16 bits for "time_hi_and_version",
        // four most significant bits holds version number 4
        mt_rand(0, 0x0fff) | 0x4000,

        // 16 bits, 8 bits for "clk_seq_hi_res",
        // 8 bits for "clk_seq_low",
        // two most significant bits holds zero and one for variant DCE1.1
        mt_rand(0, 0x3fff) | 0x8000,

        // 48 bits for "node"
        mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
    }
}

if (!function_exists('distance')){
    function distance($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 6371000)
    {
        /**
         * Calculates the great-circle distance between two points, with
         * the Vincenty formula.
         * @param float $latitudeFrom Latitude of start point in [deg decimal]
         * @param float $longitudeFrom Longitude of start point in [deg decimal]
         * @param float $latitudeTo Latitude of target point in [deg decimal]
         * @param float $longitudeTo Longitude of target point in [deg decimal]
         * @param float $earthRadius Mean earth radius in [m]
         * @return float Distance between points in [m] (same as earthRadius)
         */

        // convert from degrees to radians
        $latFrom = deg2rad(floatval($latitudeFrom));
        $lonFrom = deg2rad(floatval($longitudeFrom));
        $latTo = deg2rad(floatval($latitudeTo));
        $lonTo = deg2rad(floatval($longitudeTo));
        
        $lonDelta = $lonTo - $lonFrom;
        $a = pow(cos($latTo) * sin($lonDelta), 2) +
             pow(cos($latFrom) * sin($latTo) - sin($latFrom) * cos($latTo) * cos($lonDelta), 2);
        $b = sin($latFrom) * sin($latTo) + cos($latFrom) * cos($latTo) * cos($lonDelta);
        
        $angle = atan2(sqrt($a), $b);
        return $angle * $earthRadius;
     }
}

if (!function_exists('addHttp')){
    function addHttp($url)
    {
        if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
            $url = "http://" . $url;
        }
        return $url;
     }
}

if (!function_exists('ifNotEmpty')){
    function ifNotEmpty($item, $function = null){
        if(!empty($item)){
            if(!empty($function) && is_callable($function)){
                $item = $function($item);
            }
            return $item;
        }
        return '';
    }
}

if (!function_exists('formatNumber')){
    function formatNumber($exp, $percent = false, $currency = false, $decimal = 2)
    {
        $zeroes = "";
        for($i = 0; $i < $decimal; $i++){
            $zeroes .= "0";
        }

        $separator = $decimal == 0 ? "" : ",";

        if(!$exp && $percent && !$currency){
            return "0{$separator}{$zeroes}%";
        } else if(!$exp && !$percent && $currency){
            return "R$ 0{$separator}{$zeroes}";
        }

        if($percent){
            return number_format(($exp * 100), $decimal, ',', '.') . "%";
        }

        if($currency){
            return "R$ " . number_format($exp, $decimal, ',', '.');
        }

        return number_format($exp, $decimal, ',', '.');
    }
}

if (!function_exists('fbQuestionParse')){
    function fbQuestionParse($questions)
    {
        $questions = json_decode($questions);

        foreach($questions as $key => $value) {
            switch ($key) {
                case 'investment':
                    echo '<b>Quanto pretende investir?</b><p>'.$value.'</p>';
                    break;
                case 'when_you_want_to_make_a_decision':
                    echo '<b>Quando pretende fazer a decisão?</b><p>'.$value.'</p>';
                    break;
                case 'What_is_the_maximum_amount_you_want_to_invest?':
                    echo '<b>Qual o valor máximo que pretende investir no imóvel?</b><p>'.$value.'</p>';
                    break;
                case 'How_long_are_you_looking_for_aproperty?':
                    echo '<b>A quanto tempo busca por um imóvel?</b><p>'.$value.'</p>';
                    break;
                case 'Predicted_to_make_the_purchase_in_how_many_months?':
                    echo '<b>Prendende fazer a campra em quantos meses?</b><p>'.$value.'</p>';
                    break;
                case 'qual_o_valor_máximo_que_pretende_investir_no_imóvel?':
                    echo '<b>Qual o valor máximo que pretende inveestir no imóvel?</b><p>'.$value.'</p>';
                    break;
                case 'a_quanto_tempo_busca_por_um_imóvel?':
                    echo '<b>A quanto tempo busca por um imóvel?</b><p>'.$value.'</p>';
                    break;
                case 'pretende_realizar_a_compra_em_quantos_meses?':
                    echo '<b>Pretende realizar a compra em quantos meses?</b><p>'.$value.'</p>';
                    break;
                case 'maximum_value_installments':
                    echo '<b>Qual valor máximo que pretende pagar na parcela do imóvel?</b><p>'.$value.'</p>';
                    break;
                case 'entry_value':
                    echo 'Possui valor de entrada?<p>'.$value == 'Yes' ? 'Sim' : 'Não'.'</p>';
                    break;
            }
        }
    }
}

if (!function_exists('whatsappText')){
    function whatsappText($lead, $type)
    {
        switch ($type) {
            case 'leads.pre.qa':
                $type = 'quali';
                break;
            case 'leads.pre.sale':
                $type = 'sale';
                break;
            case 'feirao':
                break;
            default:
                $type = 'pos';
                break;
        }

        $file = \Storage::disk('public')->has('config/whatsapp/'.$type.'-text.txt');

        if($file) {
            $file = \Storage::disk('public')->get('config/whatsapp/'.$type.'-text.txt');
        }else{
            return false;
        }

        if($lead->listing == '' && $type !== 'feirao') return false;

        $maps = replaceTextTags($lead);

        foreach ($maps as $key => $map) {
            $file = str_replace($key, $map, $file);
        }

        return urlencode($file);
    }
}

if (!function_exists('preText')){
    function preText($lead, $type)
    {

        $file = \Storage::disk('public')->has('config/pre/sale-'.$type.'-text.txt');

        if($file) {
            $file = \Storage::disk('public')->get('config/pre/sale-'.$type.'-text.txt');
        }else{
            return false;
        }

        if($lead->listing == '') return false;

        $maps = replaceTextTags($lead);

        foreach ($maps as $key => $map) {
            $file = str_replace($key, $map, $file);
        }

        return $file;
    }
}

if (!function_exists('hotleadText')){
    function hotleadText($lead)
    {

        $file = \Storage::disk('public')->has('config/text/hotlead.txt');

        if($file) {
            $file = \Storage::disk('public')->get('config/text/hotlead.txt');
        }else{
            return false;
        }

        if($lead->listing == '') return false;

        $maps = replaceTextTags($lead);

        foreach ($maps as $key => $map) {
            $file = str_replace($key, $map, $file);
        }

        return $file;
    }
}

if (!function_exists('replaceTextTags')){
    function replaceTextTags($lead)
    {
        $lead->phone = isset($lead->fromphone1) ? $lead->fromphone1 : $lead->phone;
        $lead->name  = isset($lead->fromname)   ? $lead->fromname   : $lead->name;

        $common = [
            '@operador_preposicao ' => \Auth::user() ? getGender('operator', \Auth::user()->name) : '',
            '@operador_nome'    => \Auth::user() ? \Auth::user()->name : '',
            '@lead_id'          => $lead->lead_id,
            '@lead_nome'        => $lead->fromname,
            '@lead_email'       => $lead->fromemail,
            '@lead_telefone'    => $lead->phone,
            '@ajudar'           => getGender('lead', $lead->fromname),
            '@periodo'          => dayPeriod(),
            '@data_contato'     => $lead->qa && $lead->qa->first_talk_at ? $lead->qa->first_talk_at->format('d/m/Y H:s') : '',
            '@client_nome'      => $lead->user ? $lead->user->user_name : ''
        ];

        if(!$lead->listing) return $common;

        $listing = [
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
            '@empre_rua'        => $lead->listing ? $lead->listing->listing_stname : '',
            '@empre_bairro'     => $lead->listing ? $lead->listing->listing_district : '',
            '@empre_cidade'     => $lead->listing ? $lead->listing->listing_city : '',
            '@empre_estado'     => $lead->listing ? $lead->listing->listing_state : '',
        ];

        $maps = array_merge($common, $listing);

        return $maps;
    }
}

if (!function_exists('dayPeriod')){
    function dayPeriod()
    {
        $now = \Carbon\Carbon::now();

        switch ($now) {
            case $now->greaterThanOrEqualTo(\Carbon\Carbon::parse($now->format('Y-m-d').' 05:00:00')) && $now->lessThanOrEqualTo(\Carbon\Carbon::parse($now->format('Y-m-d').' 11:59:59'));
                return 'bom dia';
            case $now->greaterThanOrEqualTo(\Carbon\Carbon::parse($now->format('Y-m-d').' 12:00:00')) && $now->lessThanOrEqualTo(\Carbon\Carbon::parse($now->format('Y-m-d').' 17:59:59'));
                return 'boa tarde';
            case $now->greaterThanOrEqualTo(\Carbon\Carbon::parse($now->format('Y-m-d').' 18:00:00')) && $now->lessThanOrEqualTo(\Carbon\Carbon::parse($now->format('Y-m-d').' 04:59:59'));
                return 'boa noite';
        }
    }
}

if (!function_exists('getGender')){
    function getGender($type, $name)
    {
        $nameApi = new \App\Http\Services\Names\Names;
        $name = $nameApi->get($name);

        if($type == 'operator' && $name) {
            switch ($name['gender']) {
                case 'female':
                    return 'a '.$name['name'];
                case 'male':
                    return 'o '.$name['name'];
                case null:
                    return 'o (a) '.$name['name'];
            }

        } else if ($name) {
            switch ($name['gender']) {
                case 'female':
                    return 'ajudá-la';
                case 'male':
                    return 'ajudá-lo';
                case null:
                    return 'ajudá-lo (a) ';
            }
        }
    }
}

if (!function_exists('months')){
    function months()
    {
        return [
            'JAN' => 'JAN', 
            'FEV' => 'FEV', 
            'MAR' => 'MAR', 
            'ABR' => 'ABR', 
            'MAI' => 'MAI', 
            'JUN' => 'JUN', 
            'JUL' => 'JUL', 
            'AGO' => 'AGO',
            'SET' => 'SET', 
            'OUT' => 'OUT', 
            'NOV' => 'NOV', 
            'DEZ' => 'DEZ'
        ];
    }
}

if (!function_exists('preType')){
    function preType()
    {
        if(request()->route()->getName() == 'leads.pre.sale' || request()->get('pre_type') == '2'){
            return 'sale';
        }

        return 'qa';
    }
}

if (!function_exists('maratonaAnswers')){
    function maratonaAnswers($data) {
        switch ($data) {
            case 'n':
                return "Não";
                break;
            case 's':
                return "Sim";
                break;
            case 's/c':
                return "Sim, com carteira assinada";
                break;
            case 's/a':
                return "Sim, autônomo";
                break;
        }
    }
}

if (!function_exists('documentNameBR')){
    function documentNameBR($data) {
        switch ($data) {
            case 'identity_front':
                return "RG_frontal";
                break;
            case 'identity_back':
                return "RG_traseiro";
                break;
            case 'cpf':
                return "CPF";
                break;
            case 'rent':
                return "Renda";
                break;
            case 'address_proof':
                return "Comprovante_Residencia";
                break;
            case 'marital_status':
                return "Estado_civil";
                break;
            case 'ctps':
                return "CTPS";
                break;
            case 'fgts':
                return "FGTS";
                break;
            case 'rent_tax':
                return "Imposto_renda";
                break;
            case 'bank_extract':
                return "Extrato_bancario";
                break;
        }
    }
}
