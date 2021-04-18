<?php

namespace App\Traits;

trait StatisticTrait
{
    /**
     * Get price estimate.
     */
    public function getAvgPriceAttribute()
    {
        $statistic = $this->getStatistic();
        return ($statistic) ? $statistic->ma_average_price : null;
    }

    /**
     * Get price estimate per square meter.
     */
    public function getAvgPricePerSqMtAttribute()
    {
        $statistic = $this->getStatistic();
        return ($statistic) ? $statistic->ma_average_pricepersq : null;
    }

    /**
     * Returns the required parameters to get the statistic.
     */
    abstract protected function getStatisticParams();

    /**
     * Returns the statistic for the listing.
     */
    public function getStatistic()
    {
        $params = $this->getStatisticParams();
        return forward_static_call_array(['App\Statistic', 'get'], $params);
    }
}
