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
        if (!$this->resource) return null;
        $sender = null;
        $receiver = null;

        if ($this->sender) {
            $sender = [
                'uuid' => $this->sender->uuid,
                'name' => $this->sender->name,
                'nickname' => $this->sender->nickname,
                'avatar' => $this->sender->avatar,
                'genter' => $this->sender->genter,
                'date_of_birth' => $this->sender->date_of_birth,
            ];
        }

        if ($this->receiver) {
            $receiver = [
                'uuid' => $this->receiver->uuid,
                'name' => $this->receiver->name,
                'nickname' => $this->receiver->nickname,
                'avatar' => $this->receiver->avatar,
                'genter' => $this->receiver->genter,
                'date_of_birth' => $this->receiver->date_of_birth,
            ];
        }

        return [
            'uuid' => $this->uuid,
            'sender' => $sender,
            'receiver' => $receiver,
            'start_time' => $this->start_time,
            'nickname' => $this->nickname,
            'header_title' => $this->header_title
        ];
    }
}
