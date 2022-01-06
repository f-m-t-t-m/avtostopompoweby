<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request) : array
    {
        $arrayData = [
            'name' => $this->name,
            'surname' => $this->surname,
            'role' => $this->role,
            'avatar' => $this->avatar,
            'email' => $this->email,
        ];
        if ($this->student) {
            $arrayData['group'] = $this->student->group->name;
        }
        return $arrayData;
    }
}
