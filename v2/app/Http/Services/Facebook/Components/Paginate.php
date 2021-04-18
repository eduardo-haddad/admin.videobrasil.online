<?php 
namespace App\Http\Services\Facebook\Components;

class Paginate {

    public static function make($cursor, $index) {
        for ($i=0; $i < $index; $i++) { 
            $cursor->end();
            if($cursor->valid()) {
                $cursor->next();
            } 
        }
    }

}

?>