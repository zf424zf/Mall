<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/24 0024
 * Time: 上午 11:53
 */

namespace app\http\middleware;



class PayMiddleware extends BaseMiddleware
{
    public function handle($request, \Closure $next)
    {
        if ($this->checkOnlyUserScope()) {
            return $next($request);
        }
    }
}