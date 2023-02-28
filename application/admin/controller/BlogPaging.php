<?php

namespace app\admin\controller;

use app\common\model\Blogs;
use think\Controller;
use think\Db;
use think\facade\Request;

class BlogPaging extends Controller
{
    public function query()
    {
        $count = db('blogs')->where('delete_time',null)->count();//总数
        $limit = Request::instance()->param('limit')??$count;
        $page = Request::instance()->param('page')??1;
        $title=Request::instance()->param('title');
        if (isset($title)){
            $sql="SELECT  blogs.*,blogtype.typename FROM blogs ";
            $sql.="LEFT JOIN blogtype on blogs.tid=blogtype.tid ";
            $sql.="WHERE blogs.delete_time is null  ";
            $sql.="and title like '"."%".$title."%"."' ORDER BY bid desc ";
            $sql.="LIMIT ". ($page - 1) * $limit . ',' . $limit;
            $count=Db::name('blogs')
                ->where('title','like',"%".$title."%")
                ->where('delete_time','null')
                ->count();
        }else{
            $sql="SELECT  blogs.*,blogtype.typename FROM blogs ";
            $sql.="LEFT JOIN blogtype on blogs.tid=blogtype.tid ";
            $sql.="WHERE blogs.delete_time is null ORDER BY bid desc ";
            $sql.="LIMIT ". ($page - 1) * $limit . ',' . $limit;
        }
        $data = db('blog')->query($sql);
        return ["code" => "0", "msg" => "查询成功", "count" => $count, "data" => $data];
    }
}
