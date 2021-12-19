<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SectionResource extends JsonResource
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
            'name' => $this->name,
            'text' => $this->text,
            'file' => $this->file,
            'comments' => CommentResource::collection($this->comments),
            'created_at' => $this->created_at->format('d-m-Y H:i'),
        ];
    }
}
