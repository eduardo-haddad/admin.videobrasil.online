<?php

namespace App\Http\Services\Facebook\Controllers;

class BatchRequest extends \App\Http\Services\Facebook\GraphQL
{

    public function request($data, $node, $action, $type)
    {
        foreach($data->itens as $item){
            if($action == 'DELETE') $node = $item->id;

            $batch[] = $this->fb->request($type, '/'.$node.'/');
        }
        
        $response = $this->fb->sendBatchRequest($batch);

        return $response->getBody();
    }
}

?>
