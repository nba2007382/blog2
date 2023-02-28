<?php

namespace app\http\middleware;

class CheckLogin
{
    public function handle($request, \Closure $next)
    {
        if (!session('?admin')){
            return redirect(url('adminlogin'))->with('error','非法操作，请登录!');
        }
        return $next($request);
    }
}
