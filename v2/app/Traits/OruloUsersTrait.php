<?php

namespace App\Traits;

use App\Http\Services\Orulo\Partners;
use App\Http\Services\Orulo\Buildings;
use App\Http\Services\Orulo\Building;
use App\Http\Services\Orulo\Export\Buildings as Sheet;
use App\Client\Client;
use App\Listing\Listing;
use App\Http\Services\Orulo\Images;
use App\Orulo\OruloUsers;
use App\Jobs\GenerateReport;
use Carbon\Carbon;
use DB;

trait OruloUsersTrait
{
    public function storeUsers() {
        $orulo_api = Collect(Partners::extractBuildings());
        $db_users = Client::whereIn('user_name', $orulo_api->pluck('name')->toArray())->get();
        $orulo_users = Partners::store($orulo_api, $db_users);

        return 'true';
    }

    public function storeListings() {
        $data = new Buildings;
        $orulo_api = Collect($data->getAll());
        
        $db_listings = Listing::whereIn('listing_zapid', $orulo_api->pluck('id')->toArray())
                              ->where('listing_source', 'orulo')
                              ->get();

        return Buildings::store($orulo_api, $db_listings);
    }
}
