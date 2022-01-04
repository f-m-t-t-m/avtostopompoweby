<?php

namespace App\Http\Resources;

use App\Models\Subject;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class GroupResourceCollection extends ResourceCollection
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    protected $withoutFields = [];

    public function toArray($request) : array
    {
        return $this->processCollection($request);
    }

    public function hide(array $fields)
    {
        $this->withoutFields = $fields;
        return $this;
    }

    protected function processCollection($request)
    {
        return $this->collection->map(function (GroupResource $resource) use ($request) {
            return $resource->hide($this->withoutFields)->toArray($request);
        })->all();
    }
}
