<?php

/*

This class is only a mapping to the original UtilityManager that should
exists on the project that invokes the alert system.

Be aware that the UtilityManager can be from a different project.
Actually the grinder also has one and invokes AlertSystem classes from there,
and since it's based on require_once instead of using namespaces,
only one class called UtilityManager can be used. They both have implemented the
same functions for most of cases.

Add new functions here as they are becoming necessary.

*/

if(!class_exists('UtilityManager')){
    throw new Exception("UtilityManager class not found on Alert System context");
}

class AlertSystemUtilityManager {
    public static function getListingURL($listing)
    {
        return UtilityManager::getListingURL($listing);
    }

    public static function getPropertyItemsValue($from,$to)
    {
        return UtilityManager::getPropertyItemsValue($from,$to);
    }

    public static function isLancamento($listing)
	{
		return $listing['listing_type_id'] == 7 ? true : false;
	}

	public static function getBedroomRange($listing)
	{
		$bedFrom = max((int)$listing['listing_bedroom'],0);
		$bedTo = min($bedFrom+1,5);
		return ['from' => $bedFrom,'to' => $bedTo];
	}

	public static function getPriceRange($listing)
	{
		$per30 = round(($listing['listing_price']*30)/100);
		$priceFrom = $listing['listing_price']-$per30;
		$priceTo = $listing['listing_price']+$per30;
		return ['from' => $priceFrom, 'to' => $priceTo];
	}
}
