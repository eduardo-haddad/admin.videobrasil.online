<?php

namespace App\Traits;

trait ModelTrait
{
    /**
     * Scope a query to only include records between the given datetime range.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $column
     * @param array $range
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWhereDateTimeBetween($query, $column, $range)
    {
        return $query->whereBetween($column, [$range[0]->startOfDay(), $range[1]->endOfDay()]);
    }
}
