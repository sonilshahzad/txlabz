<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class News extends Resource
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
            'title' => $this->title,
            'created_at' => date($this->created_at),
            'updated_at' => date($this->updated_at),
            'image' => env("IMAGE_PATH").($this->image),
            'description' => $this->description,
        ];
    }
}
