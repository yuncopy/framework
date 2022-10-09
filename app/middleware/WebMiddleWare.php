<?php

namespace App\middleware;

use core\request\RequestInterface;

class WebMiddleWare
{
    public function handle(RequestInterface $request, \Closure $next)
    {

        echo "web middleware";
        return $next($request);
    }
}
