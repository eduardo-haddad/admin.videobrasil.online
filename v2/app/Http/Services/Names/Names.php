<?php
namespace App\Http\Services\Names;

use Storage;

class Names
{

    public function __construct() {
        $this->client = new \GuzzleHttp\Client;
    }

    public function get($name) {
        $name = explode(' ', $name);

        if($name[0] == '') return false;

        if(!Storage::disk('public')->has('names.json')) {
            $response = $this->client->get('https://api.genderize.io?name='.$name[0]);
            $content[] = json_decode($response->getBody()->getContents(), true);
            \Storage::put('names.json', json_encode($content));
        }

        $file = json_decode(Storage::disk('public')->get('names.json'), true);

        if(!Self::exist($file, $name)){
            Self::push($name, $file);
        };

        $file = json_decode(Storage::disk('public')->get('names.json'), true);
        $position = Self::exist($file, $name);

        if(isset($position[0]) && isset($file[$position[0]])) return $file[$position[0]];

        return false;
    }

    static public function exist($file, $name) {

        $file = json_decode(Storage::disk('public')->get('names.json'), true);
        
        $match = array_keys(
            array_filter(
                $file,
                function ($value) use ($name) {
                    return (strpos($value['name'], $name[0]) !== false);
                }
            )
        );

        return $match;
    }

    public function push($name, $file){
        $response = $this->client->get('https://api.genderize.io?name='.$name[0]);
        $content = array_merge($file, [json_decode($response->getBody()->getContents())]);
        \Storage::put('names.json', json_encode($content));
    }
}
?>