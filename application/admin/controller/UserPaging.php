<?php

namespace app\admin\controller;

use think\Controller;
use think\Db;
use think\facade\Request;
class UserPaging extends Controller
{
    public function query(){
        $count = db('users')->where('delete_time',null)->count();
        $limit = Request::instance()->param('limit')??$count;//获取limit参数，没有则为总数
        $page = Request::instance()->param('page')??1;//获取page参数，默认1（为了默认的第一次计算）
        $uname=Request::instance()->param('uname');//获取用户名关键字
        if (isset($uname)){
            $uname=trim($uname);
            $sql="select * from users WHERE delete_time is null AND ";
            $sql.="uname like '"."%".$uname."%"."' ORDER BY uid desc limit ";
            $sql.=($page - 1) * $limit . ',' . $limit;
            $count = Db::name('users')
                     ->where('uname','like',"%".$uname."%")
                     ->where('delete_time','null')
                     ->count();
        }else{
            $sql="select * from users WHERE delete_time is null ORDER BY uid desc limit ";
            $sql.=($page - 1) * $limit . ',' . $limit;
        }
        $data = db('users')->query($sql);
        return ["code" => "0", "uname"=>$uname,"msg" => "查询成功", "count" => $count, "data" => $data];
    }
}
