<?php

namespace app\index\controller;

use think\Controller;

class LoginOut extends Controller
{
     public function userLoginOut()
    {
        session(null);
        return redirect(url('index/Index/index'));
    }
}
