<?php 
if (! function_exists('getLeadgenNumber')) {
    function getLeadgenSpend($insights, $options = null) {
        if(!isset($insights['cost_per_action_type'])) return '';
        $cpl = array_keys(
            array_filter(
                $insights['cost_per_action_type'],
                    function ($value) {
                        return ($value['action_type'] == 'onsite_conversion.lead_grouped');
                    }
                )
            );

        if((isset($options['currency']) && !$options['currency']) && isset($cpl[0])){
            return $insights['cost_per_action_type'][$cpl[0]]['value'];
        }else{
            if(isset($cpl[0]) && strlen($insights['cost_per_action_type'][$cpl[0]]['value']) >= 3) return 'R$ '.number_format(substr($insights['cost_per_action_type'][$cpl[0]]['value'], 0, -2),  2, ',', '.');
        }
    }
}

if (! function_exists('getLeadgenAmount')) {
    function getLeadgenAmount($insights) {
        $leads = array_keys(
            array_filter(
                $insights['actions'],
                    function ($value) {
                        return ($value['action_type'] == 'onsite_conversion.lead_grouped');
                    }
                )
            );
            
        if(isset($leads[0])) return $insights['actions'][$leads[0]]['value'];
    }
}

if (! function_exists('getAdsetBudgets')) {
    function getAdsetBudgets($adsets) {
        $adsets_budgets = [];

        foreach($adsets as $adset) {
            $adsets_budgets[] = [
                'adset_id' => $adset->id,
                'daily_budget' => '100'
            ];
        }

        return json_encode($adsets_budgets);
    }
}

?>