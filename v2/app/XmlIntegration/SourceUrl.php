<?php

namespace App\XmlIntegration;

use Illuminate\Database\Eloquent\Model;

class SourceUrl extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */

    protected $table = 'res_listings_source_url_arq';

    protected $connection = 'ai_arq';


    /**
     * The primary key column.
     *
     * @var string
     */
    protected $primaryKey = 'listing_fk';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['listing_fk'];


    /**
     * @param $id
     * @param $photos
     * @param bool $insert
     * @return array
     */
    public static function prepareBatchArray($id, $photos, $insert = false){

        if($insert){
            return [
                $id, // listing_fk
                '0', // listing_photo_status
                $photos['listing_photo_url'] // listing_photo_url
            ];
        }

        $array = [
            'listing_photo_status' => '0',
            'listing_fk' => $id,
        ];

        if(!empty($photos['listing_photo_url'])){
            $array = array_merge($array, [
                'listing_photo_url' => $photos['listing_photo_url']
            ]);
        }

        $array = array_merge($array, [
            'listing_original_url' => !empty($photos['listing_url']) ? $photos['listing_url'] : ''
        ]);

        return $array;
    }

}
