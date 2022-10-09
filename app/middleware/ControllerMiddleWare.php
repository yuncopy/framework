<?php

namespace App\middleware;

use core\request\RequestInterface;

class ControllerMiddleWare
{
    public function handle(RequestInterface $request, \Closure $next)
    {

        echo "<hr/>controller middleware<hr/>";
        return $next($request);
    }
}
