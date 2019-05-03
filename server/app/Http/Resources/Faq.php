<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class Faq extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return array_merge(parent::toArray($request), [
            'title_sim' => str_limit($this->title, 20),
            'description_sim' => str_limit($this->description),
            'link' => $this->getLink()
        ]);
    }
}
