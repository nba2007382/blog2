<?php

namespace app\admin\controller;

use think\Controller;
use think\Db;
use think\facade\Request;

class Link extends Controller
{
    //显示友情链接
    public function linkList()
    {
        return view('admin@link/linklist');
    }
    //添加链接
    public function linkAdd(){
        return view('admin@link/linkadd');
    }

    //删除链接
    public function linkDel()
    {
        $lid = Request::get('lid');
        $res = db('link')->where('lid', $lid)->useSoftDelete('delete_time', time())->delete();
        if ($res){
            return json(['code'=>1,'msg'=>'删除成功']);
        }else{
            return json(['code'=>0,'msg'=>'删除失败']);
        }
    }

    //修改链接
    public function linkEdit()
    {
        $lid = Request::instance()->get('lid');
        $data = \app\common\model\Link::find($lid);
        return view('admin@link/linkedit', compact('data'));
    }

    //***************************** 分割线************************************************
    //添加链接
    public function doLinkAdd(){
        $res=\app\common\model\Link::create(Request::post());
        return json(['code'=>1,'msg'=>'添加成功']);
    }

    //处理链接修改
    public function doLinkEdit(){
     $data=Request::post();
     $lname=$data['lname'];
     $url=$data['url'];
     $lid=$data['lid'];
     $res=Db::name('link')->where('lid',$lid)->update([
         'lname'=>$lname,
         'url'=>$url
     ]);
//        $res=\app\common\model\Link::update($data);
     return ['code'=>1,'msg'=>'更新成功！','res'=>$res,'lid'=>$lid,'url'=>$url,'data'=>$data];
    }

    //处理链接显示数据
    public function linkShow()
    {
        $count = \app\common\model\Link::where('delete_time', null)->count();
        $limit = Request::instance()->param('limit')??$count;//获取limit参数，没有则为总数
        $page = Request::instance()->param('page')??1;//获取page参数，默认1（为了默认的第一次计算）
        $lname=Request::instance()->param('lname');//获取域名关键字
        if (isset($lname)){
            $lname=trim($lname);
            $sql="SELECT * FROM link WHERE delete_time is null ";
            $sql.="AND lname like '"."%".$lname."%"."' limit ";
            $sql.=($page - 1) * $limit . ',' . $limit;
            $count=Db::name('link')
                ->where('lname','like',"%".$lname."%")
                ->where('delete_time','null')
                ->count();
        }else{
            $sql = "SELECT * FROM link WHERE delete_time is null ORDER BY lid desc LIMIT ";
            $sql .= ($page - 1) * $limit . ',' . $limit;
        }
        $data = db('link')->query($sql);
        return ['code' => 0, "msg" => "查询成功", 'count' => $count, 'data' => $data];
    }
}