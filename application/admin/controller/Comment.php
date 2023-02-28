<?php

namespace app\admin\controller;

use app\common\model\Comments;
use think\Controller;
use think\Db;
use think\facade\Request;

class Comment extends Controller
{
    //显示评论视图
    public function commentList()
    {
        return view('admin@comment/commentlist');
    }

    //删除评论
    public function commentDel()
    {
       $cid=Request::instance()->get('cid');
       $res=Comments::where('cid',$cid)->delete();
        if ($res) {
            return ['code' => 1, 'msg' => '评论已删除~'];
        } else {
            return ['code' => 0, 'msg' => '删除失败，请稍后再试~'];
        }
    }
    //**********************************分割线*********************************************
    //显示评论视图所需数据获取
    public function commentListQuery()
    {
        $count = Comments::count();
        $limit = Request::instance()->param('limit')??$count;//获取limit参数，没有则为总数
        $page = Request::instance()->param('page')??1;//获取page参数，默认1（为了第一次计算）
        $content=Request::instance()->param('content');
        if (isset($content)){
            $sql="SELECT comments.*,blogs.title,blogs.intro,blogs.pic,users.uname FROM comments ";
            $sql.="LEFT JOIN blogs on comments.bid=blogs.bid ";
            $sql.="LEFT JOIN users on comments.uid=users.uid ";
            $sql.="WHERE content like "."'%".$content."%' ";
            $sql.="ORDER BY cid DESC ";
            $sql.="LIMIT ". ($page - 1) * $limit . ',' . $limit;
            $count=Db::name('comments')
                ->where('content','like',"%".$content."%")
                ->count();

        }else{
            $sql="SELECT comments.*,blogs.title,blogs.intro,blogs.pic,users.uname FROM comments ";
            $sql.="LEFT JOIN blogs on comments.bid=blogs.bid ";
            $sql.="LEFT JOIN users on comments.uid=users.uid ";
            $sql.="ORDER BY cid DESC ";
            $sql.="LIMIT ". ($page - 1) * $limit . ',' . $limit;
        }
        $data=db('blog')->query($sql);
        return ["code" => "0", "msg" => "查询成功", "count" => $count, "data" => $data];
    }
}
