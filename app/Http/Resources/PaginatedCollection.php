<?php

namespace App\Http\Resources;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Str;

/** @SuppressWarnings(PHPMD.NumberOfChildren) */
class PaginatedCollection extends ResourceCollection
{
    protected $resourceName = null;

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function toArray($request)
    {
        $colelections = $this->resource;
        if ($this->resource instanceof LengthAwarePaginator) {
            $colelections = $this->resource->getCollection();
        }

        foreach ($colelections as $i => $colelection) {
            if (!$colelection) {
                unset($colelections[$i]);
            }
        }

        $response = [
            'data' => $this->getResourceClass()::collection($colelections)
        ];
        if ($this->resource instanceof LengthAwarePaginator) {
            $response['current_page'] = $this->resource->currentPage();
            $response['last_page'] = $this->resource->lastPage();
            $response['per_page'] = $this->resource->perPage();
            $response['total'] = $this->resource->total();
        }

        return $response;
    }

    /**
     * Get resource class name from collection
     *
     * @return string
     */
    protected function getResourceClass()
    {
        return $this->resourceName ?? Str::replaceLast('Collection', 'Resource', get_class($this));
    }
}
