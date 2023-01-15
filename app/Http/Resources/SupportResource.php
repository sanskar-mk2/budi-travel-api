<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SupportResource extends JsonResource
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
            'topic' => $this->topic,
            'subject' => $this->subject,
            'message' => $this->message,
            'created_at' => $this->created_at->diffForHumans(),
            'user' => new BareUserResource($this->user),
            'resolved' => $this->resolved,
        ];
    }
}
