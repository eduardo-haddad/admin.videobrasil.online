<?php

namespace App\Helpers;

use \Illuminate\Pagination\LengthAwarePaginator as Paginate;

class Paginator {
    public static function paginateCollection($collection, $perPage, $pageName = 'page', $fragment = null)
    {
        $currentPage = Paginate::resolveCurrentPage($pageName);
        $currentPageItems = $collection->slice(($currentPage - 1) * $perPage, $perPage);
        parse_str(request()->getQueryString(), $query);
        unset($query[$pageName]);
    
        $paginator = new Paginate(
            $currentPageItems,
            $collection->count(),
            $perPage,
            $currentPage,
            [
                'pageName' => $pageName,
                'path' => Paginate::resolveCurrentPath(),
                'query' => $query,
                'fragment' => $fragment
            ]
        );
    
        return $paginator;
    }
}
?>