<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'created_at' => $this->created_at->diffForHumans(),
            'user' => new UserResource($this->user),
            'task' => $this->task,
            'completed' => $this->completed === null ? false : Carbon::parse($this->completed)->diffForHumans()
        ];
    }
}
