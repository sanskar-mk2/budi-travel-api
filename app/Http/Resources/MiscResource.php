<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MiscResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'address' => $this->address,
            'tagline' => $this->tagline,
            'total_projects' => $this->total_projects,
            'total_offers' => $this->total_offers,
            'total_completed' => $this->total_completed,
        ];
    }
}
