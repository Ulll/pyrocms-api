<?php

namespace Pyrocmsapi\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\Response;

class BuildHttpResponse
{
    public function handle($request, Closure $next, $guard = null)
    {
        $response = $next($request);
        //数据包装格式
        $baseFormat = array(
            'errno'  => 0,
            'errmsg' => '',
            'data'   => '',
        );
        $e = $response->exception;
        //生产环境不抛出异常
        if ($e instanceof Exception) {
            if (strtolower(getenv('APP_ENV')) == 'production') {
                $newData = array_merge($baseFormat, [
                    'errno' => $e->getMessage(),
                    'errmsg' => $e->getMessage().' Occured In '.$e->getFile().' with line:'.$e->getLine()
                ]);
                return $response->setContent(json_encode($newData));
            }
        }else {
            //正常的数据
            $original = $response->getOriginalContent();
            //成功的数据
            if (is_array($original)) {
                $newData = array_merge($baseFormat, $original);
                return $response->setContent(json_encode($newData));
            }
        }
        return $response;
    }
}
