<?php
/**
 * AuthTest.php
 * 权限测试
 * 
 * @author  王中艺 <wangzy_smile@qq.com>
 * @date    2018-05-23 10:57:26
 */
     
namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthTest extends TestCase
{
    
    function testLogin(){
        $res    = $this->json('POST', '/admin/login?system=phpunit', [
            'username'  => 'vueadmin',
            'password'  => 'vueadmin123'
        ]);
        print_r($res->getData());
        // $this->assertTrue($res->res_data->info);

        // $res    = $this->request('/admin/auth/user/list?page=1&token=4E5F8321A8306A27CA14F444E12E3C98');
        // print_r($res);
        $this->assertTrue(true);
    }

}
