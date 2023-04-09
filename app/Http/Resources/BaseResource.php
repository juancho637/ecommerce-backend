<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Parameter(
 *     parameter="relationships--include",
 *     name="include",
 *     description="Relationships of resource",
 *     required=false,
 *     in="query",
 *     @OA\Schema(
 *         type="string"
 *     )
 * ),
 * @OA\Parameter(
 *     parameter="filter--search",
 *     name="search",
 *     description="String to search",
 *     required=false,
 *     in="query",
 *     @OA\Schema(
 *         type="string"
 *     )
 * ),
 */
class BaseResource extends JsonResource
{
    public function toArray($request)
    {
        //
    }
}
