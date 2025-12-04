<?php

namespace App\Traits;

trait ApiResponse
{
    public function apiPagination($paginationData)
    {
        return [
            'data' => $paginationData->items(),
            'pagination' => [
                'total' => $paginationData->total(),
                'per_page' => $paginationData->perPage(),
                'current_page' => $paginationData->currentPage(),
                'last_page' => $paginationData->lastPage(),
                'next_page_url' => $paginationData->nextPageUrl(),
                'prev_page_url' => $paginationData->previousPageUrl(),
            ]
        ];
    }
}