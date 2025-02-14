<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

class SortedUsersResource extends UserResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            ...parent::toArray($request),
            'total_posts_views' => $this->total_posts_views,
        ];
    }
}
