<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Admin\User;
use App\Services\Admin\Auth;
use App\Exceptions\NormalException;
use SmileYi\Utils\Token;

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
        $token = $request->header('authorized_token');
        
        if (\App::environment('local') and !$token) {
            $request->admin = User::where([
                'id' => 1
            ])->first();
        } 

        if ($token) {
            $request->admin = User::where([
                ['token', '=', $token],
                ['token_deadline', '>=', date('Y-m-d H:i:s')],
                ['status', '=', User::STATUS_NORMAL]
            ])->first();
        }
            

        $path = $request->path();
        //登录检测
        if(!in_array($path, $this->noNeedLogin) && (!$request->admin || $request->admin->status != 1)){
            throw new NormalException(601);
        }

        //权限检测
        if (!in_array($path, $this->noNeedLogin) 
            && !in_array($request->admin->id, config('auth.admin.root_ids'))
            && !in_array($path, $this->noNeedAuth) 
            && !Auth::check($request->admin, $path)) {
            throw new NormalException(609, $request->admin->id);
        }

        return $next($request);
    }
}
