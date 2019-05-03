<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Admin\User;
use App\Services\Admin\Auth;
use App\Exceptions\NormalException;

class AdminAuth
{
    protected $noNeedLogin  = [
        'admin/login',
    ];

    protected $noNeedAuth   = [
        'admin/menus',
        'admin/detail',
        'admin/update',
        'admin/upload',
        'admin/logout'
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        
        $token  = $request->input('token');
        if(\App::environment('local') and !$token){
            $token = '790F65FB3F8408310569A3C1945E281D';
        }
        if($token){
            $request->admin     = User::where([
                ['token', '=', $token],
                ['token_deadline', '>=', date('Y-m-d H:i:s')],
                ['status', '=', 1]
            ])->first();
        }

        $path   = $request->path();
        //登录检测
        if(!in_array($path, $this->noNeedLogin) && (!$request->admin || $request->admin->status != 1)){
            throw new NormalException(601);
        }

        //权限检测
        if(!in_array($path, $this->noNeedLogin) 
            && $request->admin->id != 1 
            && !in_array($path, $this->noNeedAuth) 
            && !Auth::check($request->admin, $path)){
            throw new NormalException(806, $request->admin->id);
        }

        return $next($request);
    }
}
