<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use App\Utils\Log;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        $httpCode    = method_exists($exception, 'getStatusCode') 
            ? $exception->getStatusCode() : 500;
            
        $resData    = [
            'message'  => $exception->getMessage(),
            'code'  => $exception->getCode()
        ];

        if(!\App::environment('pro')){
            $resData   = array_merge($resData, [
                'file'  => $exception->getFile(),
                'line'  => $exception->getLine(),
                'trace' => array_slice($exception->getTrace(), 0, 3)
            ]);
        }

        //响应数据整理
        $response   = [
            'http_code' => $httpCode,
            'result'=> 'fail',
            'data'  => $resData,
        ];
        
        //日志写入
        Log::getInstance('file')->put('exception', [
            'uri' => $request->path(),
            'request' => $request->all(),
            'response' => $response
        ]);

        // if(\App::environment('local')){
        //     return parent::render($request, $exception);
        // }

        //后台响应json数据
        if(preg_match('/^admin.+$/', $request->path())){
            return response()->json($response, 200);
        }
        
        return response()->view('404', ['response' => $response]);
    }
}
