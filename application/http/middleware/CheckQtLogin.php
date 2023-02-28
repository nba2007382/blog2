<?php

namespace app\http\middleware;

class CheckQtLogin
{
    public function handle($request, \Closure $next)
    {
         if (!session('?user')){
            return redirect(url('index/login/login'))->with('error','非法操作，请登录!');
        }
          return $next($request);
    }
}
