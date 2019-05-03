<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class Game extends Resource
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
            'faq_link' => $this->getFaqLink(),
        ]);
    }
}
