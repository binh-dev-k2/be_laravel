<?php

namespace App\Http\Resources\Couple;

use Illuminate\Http\Resources\Json\JsonResource;

class CoupleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        return [
            'uuid' => $this->uuid,
            'sender' => $this->sender,
            'receiver' => $this->receiver,
            'start_time' => $this->start_time,
            'nickname' => $this->nickname,
            'header_title' => $this->header_title
        ];
    }
}
