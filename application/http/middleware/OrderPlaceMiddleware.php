<?php

namespace app\http\middleware;

class OrderPlaceMiddleware extends BaseMiddleware
{
    public function handle($request, \Closure $next)
    {
        if ($this->checkOnlyUserScope()) {
            return $next($request);
        }
    }
}
