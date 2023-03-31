<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
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
            'offer' => OfferResource::make($this->offer),
            'created_at' => $this->created_at->diffForHumans(),
            'updated_at' => $this->updated_at->diffForHumans(),
            'user' => new BareUserResource($this->user),
            'price' => number_format($this->price, 2, '.', ''),
            'paid_with_balance' => $this->paid_with_balance,
            'user_finished_at' => $this->user_finished_at,
            'agent_finished_at' => $this->agent_finished_at,
            'status' => $this->user_finished_at && $this->agent_finished_at ? 'Completed' : 'In Progress',
        ];
    }
}
