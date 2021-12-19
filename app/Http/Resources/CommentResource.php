<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
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
            'text' => $this->text,
            'author' => new UserResource($this->user),
            'file' => $this->file,
            'created_at' => $this->created_at->format('d-m-Y H:i'),
        ];
    }
}
