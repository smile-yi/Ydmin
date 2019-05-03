<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\Resource;

class User extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $field = [
            'id' => $this->id,
            'username' => $this->username,
            'nickname' => $this->nickname,
            'status' => $this->status,
        ];

        return $field;
    }
}
