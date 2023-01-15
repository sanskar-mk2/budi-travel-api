<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
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
            'profile_picture' => $this->profile_picture,
            'instagram_handle' => $this->instagram_handle,
            'tiktok_handle' => $this->tiktok_handle,
            'facebook_handle' => $this->facebook_handle,
        ];
    }
}
