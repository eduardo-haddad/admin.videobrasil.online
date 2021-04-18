<?php

namespace App\Http\Services\Facebook\Controllers;

use FacebookAds\Object\AdCreative;
use FacebookAds\Object\AdImage;
use FacebookAds\Object\Fields\AdImageFields;
use FacebookAds\Object\Fields\AdCreativeFields;
use FacebookAds\Object\AdCreativeObjectStorySpec;
use FacebookAds\Object\Fields\AdCreativeObjectStorySpecFields;
use FacebookAds\Object\AdCreativeLinkData;
use FacebookAds\Object\Fields\AdCreativeLinkDataFields;
use FacebookAds\Object\Values\AdCreativeCallToActionTypeValues;
use FacebookAds\Object\Values\AdPreviewAdFormatValues;
use FacebookAds\Object\Fields\AdPreviewFields;
use FacebookAds\Object\AdAccount;

class Creative extends \App\Http\Services\Facebook\SDK
{
    public function createImg($img){
        $creativeImg = $this->account->createAdImage([], [
            AdImageFields::FILENAME => $img
        ]);

        return $creativeImg->images;
    }

    //
    public function create($data, $img){
        $link_data = new AdCreativeLinkData();
        $link_data->setData(array(
            AdCreativeLinkDataFields::NAME           => $data['title'],
            AdCreativeLinkDataFields::MESSAGE        => $data['description'],
            AdCreativeLinkDataFields::LINK           => 'https://agenteimovel.com.br',
            AdCreativeLinkDataFields::DESCRIPTION    => $data['subtitle'],
            AdCreativeLinkDataFields::IMAGE_HASH     => $img['hash'],
            AdCreativeLinkDataFields::CALL_TO_ACTION => [
                'type'  => $data['call_to_action'],
                'value' => [
                    'lead_gen_form_id' => $data['form'],
                    'link' => 'https://agenteimovel.com.br'
                    ]
            ]
        ));

        $object_story_spec = new AdCreativeObjectStorySpec();

        $object_story_spec->setData(array(
            AdCreativeObjectStorySpecFields::PAGE_ID   => '277823286593',
            AdCreativeObjectStorySpecFields::LINK_DATA => $link_data,
        ));

        $creativeAd = $this->account->createAdCreative([], [
            AdCreativeFields::NAME              => 'Imagem de AD - '.$data['listing'],
            AdCreativeFields::TITLE             => $data['title'],
            AdCreativeFields::OBJECT_STORY_SPEC => $object_story_spec
        ]);

        // $creativeAd = new AdCreative();
        // $creativeAd->setData([
        //     AdCreativeFields::NAME              => 'Imagem de AD - '.$data['listing'],
        //     AdCreativeFields::OBJECT_STORY_SPEC => $object_story_spec
        // ]);

        return $creativeAd;
    }

    public function update($data, $img = null){
        $creativeOld = new AdCreative($data['creative_id']);
        $creativeOld = $creativeOld->getSelf(['image_hash', 'object_story_spec', 'call_to_action_type'], []);

        if(!isset($img)){
            $img['hash'] = $creativeOld->image_hash;
        }

        $link_data = new AdCreativeLinkData();
        $link_data->setData([
            AdCreativeLinkDataFields::NAME           => $data['title'],
            AdCreativeLinkDataFields::MESSAGE        => $data['description'],
            AdCreativeLinkDataFields::LINK           => 'https://agenteimovel.com.br',
            AdCreativeLinkDataFields::DESCRIPTION    => $data['subtitle'],
            AdCreativeLinkDataFields::IMAGE_HASH     => $img['hash'],
            AdCreativeLinkDataFields::CALL_TO_ACTION => [
                'type'  => $data['call_to_action'],
                'value' => [
                    'lead_gen_form_id' => $data['form'],
                    'link' => 'https://agenteimovel.com.br'
                    ]
            ]
        ]);

        $object_story_spec = new AdCreativeObjectStorySpec();

        $object_story_spec->setData([
            AdCreativeObjectStorySpecFields::PAGE_ID   => '277823286593',
            AdCreativeObjectStorySpecFields::LINK_DATA => $link_data,
        ]);

        $creativeAd = $this->account->createAdCreative([], [
            AdCreativeFields::NAME              => 'Imagem de AD - '.$data['listing'],
            AdCreativeFields::TITLE             => $data['title'],
            AdCreativeFields::OBJECT_STORY_SPEC => $object_story_spec
        ]);

        return ['new' => $creativeAd, 'old' => $creativeOld];
    }

    public function getPreview($data, $img, $update = null){
        if($update){
            $creative = Self::update($data, $img);
            $creative = $creative['new'];
        }else{
            $creative = Self::create($data, $img);
        }

        $preview = $creative->getPreviews([], [
            AdPreviewFields::AD_FORMAT => AdPreviewAdFormatValues::DESKTOP_FEED_STANDARD,
        ]);

        $creative->deleteSelf();

        return json_encode($preview->getObjects()[0]->body);
    }
}

?>
