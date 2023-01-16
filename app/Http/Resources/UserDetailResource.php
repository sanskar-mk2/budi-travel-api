<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserDetailResource extends JsonResource
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
            'onboarded' => $this->onboarded,
            'terms_accepted' => $this->terms_accepted,
            'privacy_accepted' => $this->privacy_accepted,
            'push_notifications' => $this->push_notifications,
            'active' => $this->active,
        ];
    }
}
