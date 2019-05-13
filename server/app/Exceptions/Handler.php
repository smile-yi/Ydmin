<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use SmileYi\Utils\Log;

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
        $resData = [
            'errno' => 1,
            'errmsg' => '请求出错',
            'data' => []
        ];
        if ($exception instanceof NormalException) {
            $resData['errno'] = $exception->getCode();
            $resData['errmsg'] = $exception->getMessage();
        }

        if(!\App::environment('pro')){
            $resData['data'] = [
                'code' => $exception->getCode(),
                'message' => $exception->getMessage(), 
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'trace' => array_slice($exception->getTrace(), 0, 3)
            ];
        }
        
        //日志写入
        Log::getInstance('file')->put('exception', [
            'uri' => $request->path(),
            'request' => $request->all(),
            'response' => $resData
        ]);

        return response()->json($resData);
    }
}
