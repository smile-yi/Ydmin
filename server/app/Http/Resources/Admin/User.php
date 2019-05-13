<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\Resource;
use SmileYi\Utils\Common;
use SmileYi\Utils\ArrTool;

class User extends Resource
{

    const MAP_STATUS = [
        1 => '正常',
        0 => '禁用',
        -1 => '删除'
    ];

    const DEFAULT_AVATAR = 'default_avatar';

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $field = array_merge(parent::toArray($request), [
            'status_text' => self::MAP_STATUS[$this->status],
            'last_login_ip' => $this->last_login_ip ? long2ip($this->last_login_ip) : '',
            'avatar' => $this->avatar ?? self::DEFAULT_AVATAR
        ]);
        unset($field['password']);

        //非特定接口需要隐藏特定字段
        if (!in_array($request->path(), [
            'admin/login', 'admin/detail', 'admin/update'
        ])) {
            $field = ArrTool::leach($field, [
                'username', 'nickname', 'avatar', 'email', 'status', 'status_text',
                'created_at'
            ]);
        }

        return $field;
    }
}
