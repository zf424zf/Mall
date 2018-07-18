<?php

namespace app\http\middleware;

use think\Middleware;
use app\api\service\Token as TokenService;
class BaseMiddleware
{
    public function handle($request, \Closure $next)
    {
        return $next($request);
    }

    //检查当前用户权限，只有用户权限才能访问
    public function checkOnlyUserScope()
    {
         return TokenService::needOnlyUserScope();
    }

    //检查当前用户权限，至少用户权限才能访问
    public function checkMinRuleUserScope()
    {

        return TokenService::needMinRuleUserScope();
    }
}
