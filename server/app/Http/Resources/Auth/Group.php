<?php

namespace App\Http\Resources\Auth;

use Illuminate\Http\Resources\Json\Resource;

class Group extends Resource
{
    const MAP_STATUS = [
        1 => '正常',
        0 => '禁用'
    ];

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return array_merge(parent::toArray($request), [
            'status_text' => self::MAP_STATUS[$this->status]
        ]);
    }
}
