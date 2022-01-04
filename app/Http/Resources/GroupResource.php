<?php

namespace App\Http\Resources;

use App\Models\Subject;
use Illuminate\Http\Resources\Json\JsonResource;

class GroupResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public static function collection($resource)
    {
        return tap(new GroupResourceCollection($resource), function ($collection) {
            $collection->collects = __CLASS__;
        });
    }

    protected $withoutFields = [];

    public function toArray($request) : array
    {
        return $this->filterFields([
            'id' => $this->id,
            'name' => $this->name,
            'short_name' => $this->short_name,
            'code' => $this->code,
            'subjects' => SubjectResource::collection($this->subjects),
        ]);
    }

    public function hide(array $fields)
    {
        $this->withoutFields = $fields;
        return $this;
    }

    protected function filterFields($array)
    {
        return collect($array)->forget($this->withoutFields)->toArray();
    }
}
