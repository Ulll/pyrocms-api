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
        //失败的数据
        $e = $response->exception;
        if ($e instanceof Exception) {
            $newData = array_merge($baseFormat, [
                'errno' => $e->getMessage(),
                'errmsg' => $e->getMessage().' Occured In '.$e->getFile().' with line:'.$e->getLine()
            ]);
        }else {
            $original = $response->getOriginalContent();
            //成功的数据
            if (is_array($original)) {
                $newData = array_merge($baseFormat, $original);
            }
        }
        return $response->setContent(json_encode($newData));
    }
}
