<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request) : array
    {
        return [
            'id' => $this->id,
            'text' => $this->text,
            'read' => $this->read,
            'user_id' => $this->user_id,
            'subject_id' => $this->subject_id,
            'section_id' => $this->section_id
        ];
    }
}
