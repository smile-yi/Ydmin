<?php

namespace App\Http\Middleware;
use App\Exceptions\NormalException;
use App\Utils\Log as LogUtil;
use Illuminate\Support\Facades\DB;
use Closure;

class Log
{

    static $noLogPath = [
        'public/log',
        'public/image-code',
        'admin/game/download-faq-tpl'
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
        if(in_array($request->path(), self::$noLogPath)){
            return $next($request);
        }

        DB::enableQueryLog();        

        $response   = $next($request);
        $log = [
            'uri' => $request->path(),
            'user' => $request->user ? $request->user->id : 'null',
            'admin' => $request->admin ? $request->admin->id : 'null',
            'request' => $request->all(),
            // 'response' => $response->original,
            'sql' => DB::getQueryLog()
        ];
        LogUtil::getInstance('file')->put('http', $log);

        if(is_array($response->original)){
            return response()->json(array_merge($response->original, [
                'sql'   => DB::getQueryLog()
            ]));
        }
        
        return $response;     
    }
}
