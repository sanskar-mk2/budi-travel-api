<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
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
            'id' => $this->id,
            'sender' => BareUserResource::make($this->sender),
            'receiver' => BareUserResource::make($this->receiver),
            'message' => $this->message,
            'created_at' => $this->created_at->diffForHumans(),
        ];
    }
}
