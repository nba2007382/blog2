<?php

namespace app\admin\controller;

use app\common\model\Blogtype;
use app\common\model\Users;
use think\Controller;
use think\Db;
use think\facade\Request;

class DataRecoveryCenter extends Controller
{
    //*****************************用户数据恢复区*************************************
    //显示待恢复的用户视图
    public function recoveryUserList()
    {
        return view('admin@datarecoverycenter/recoveryuserlist');
    }

    //处理显示视图数据（搜索+分页）
    public function recoveryUserQuery()
    {
        $data = Users::onlyTrashed()->select();
        $count = Users::onlyTrashed()->count();
        $limit = Request::instance()->param('limit') ?? $count;
        $page = Request::instance()->param('page') ?? 1;
        $uname = Request::instance()->param('uname');
        if (isset($uname)) {
            $uname = trim($uname);
            $sql = "select * from users WHERE delete_time is not null AND ";
            $sql .= "uname like '" . "%" . $uname . "%" . "' limit ";
            $sql .= ($page - 1) * $limit . ',' . $limit;
            $count = Db::name('users')
                ->where('uname', 'like', "%" . $uname . "%")
                ->where('delete_time is not null')
                ->count();
        } else {
            $sql = "select * from users WHERE delete_time is not null ORDER BY uid desc limit ";
            $sql .= ($page - 1) * $limit . ',' . $limit;
        }
        $data = db('blogs')->query($sql);
        return ["code" => "0", "uname" => $uname, "msg" => "查询成功", "count" => $count, "data" => $data];
    }

    //还原用户帐号
    public function recoveryUser()
    {
        $uid = Request::instance()->get('uid');
        $res = Db::name('users')->where('uid', $uid)->update(['delete_time' => null]);
        if ($res) {
            return ['code' => 1, 'msg' => '帐号已还原~'];
        } else {
            return ['code' => 0, 'msg' => '还原失败，请稍后再试~'];
        }
    }

    //永久删除用户帐号
    public function recoveryUserDel()
    {
        $uid = Request::instance()->get('uid');
        $res=Db::name('users')->where('uid',$uid)->delete();
        if ($res) {
            return ['code' => 1, 'msg' => '帐号已永久性删除~'];
        } else {
            return ['code' => 0, 'msg' => '删除失败，请稍后再试~'];
        }
    }
    //*****************************博文数据恢复区*************************************

    //显示待恢复的博文视图
    public function recoveryBlogList()
    {
        return view('admin@datarecoverycenter/recoverybloglist');

    }
    //处理显示博文视图所需数据
    public function recoveryBlogQuery(){
        $count =\app\common\model\Blogs::onlyTrashed()->count();
        $limit = Request::instance()->param('limit')??$count;//获取limit参数，没有则为总数
        $page = Request::instance()->param('page')??1;//获取page参数，默认1（为了第一次计算）
        $title=Request::instance()->param('title');
        if (isset($title)){
            $sql="SELECT  blogs.*,blogtype.typename,admins.adminname FROM blogs ";
            $sql.="LEFT JOIN blogtype on blogs.tid=blogtype.tid ";
            $sql.="LEFT JOIN admins on blogs.uploadadmin=admins.adminid ";
            $sql.="WHERE blogs.delete_time is not null  ";
            $sql.="and title like '"."%".$title."%"."' ORDER BY bid desc ";
            $sql.="LIMIT ". ($page - 1) * $limit . ',' . $limit;
            $count=Db::name('blogs')
                ->where('title','like',"%".$title."%")
                ->where('delete_time is not null')
                ->count();
        }else{
            $sql="SELECT  blogs.*,blogtype.typename,admins.adminname FROM blogs ";
            $sql.="LEFT JOIN blogtype on blogs.tid=blogtype.tid ";
            $sql.="LEFT JOIN admins on blogs.uploadadmin=admins.adminid ";
            $sql.="WHERE blogs.delete_time is not null ORDER BY bid desc ";
            $sql.="LIMIT ". ($page - 1) * $limit . ',' . $limit;
        }
        $data = db('blogs')->query($sql);
        return ["code" => "0", "msg" => "查询成功", "count" => $count, "data" => $data];
    }
    //还原博文
    public function recoveryBlog(){
        $bid = Request::instance()->get('bid');
        $res = Db::name('blogs')->where('bid', $bid)->update(['delete_time' => null]);
        if ($res) {
            return ['code' => 1, 'msg' => '博文已还原~'];
        } else {
            return ['code' => 0, 'msg' => '还原失败，请稍后再试~'];
        }
    }
    //永久删除博文
    public function recoveryBlogDel(){
        $bid = Request::instance()->get('bid');
        $res=Db::name('blogs')->where('bid',$bid)->delete();
        if ($res) {
            return ['code' => 1, 'msg' => '博文已永久性删除~'];
        } else {
            return ['code' => 0, 'msg' => '删除失败，请稍后再试~'];
        }
    }

    //*****************************博客分类数据恢复区*************************************

    //显示待恢复的博客类别视图
    public function recoveryBlogTypeList()
    {
        return view('admin@datarecoverycenter/recoveryblogtypelist');

    }
    //处理博客类别视图显示所需数据
    public function recoveryBlogtypeQuery(){
        $count = Db::name('blogtype')->where('delete_time is not null')->count();
        $typename=Request::get('typename');//获取类别关键字
        if (isset($typename)){
            $typename=trim($typename);
            $sql="select *from blogtype where delete_time is not null AND ";
            $sql.="typename like '"."%".$typename."%"."'"."order by tid desc";
            $count=Db::name('blogtype')
                ->where('delete_time is not null')
                ->where('typename','like',"%".$typename."%")
                ->count();
            $data=db('blog')->query($sql);

        }else{
            $sql="select * from blogtype WHERE delete_time is not null order by tid desc";
            $data=db('blogtype')->query($sql);
        }
        return ["code" => "0","typename"=>$typename,"msg" => "查询成功", "count" => $count, "data" => $data];
    }
    //还原博客分类
    public function recoveryBlogtype(){
        $tid = Request::instance()->get('tid');
        $res = Db::name('blogtype')->where('tid', $tid)->update(['delete_time' => null]);
        if ($res) {
            return ['code' => 1, 'msg' => '博文已还原~'];
        } else {
            return ['code' => 0, 'msg' => '还原失败，请稍后再试~'];
        }
    }
    //永久删除博客类别
    public function recoveryBlogtypeDel(){
        $tid = Request::instance()->get('tid');
        $res=Db::name('blogtype')->where('tid',$tid)->delete();
        if ($res) {
            return ['code' => 1, 'msg' => '博文已永久性删除~'];
        } else {
            return ['code' => 0, 'msg' => '删除失败，请稍后再试~'];
        }
    }
    //*****************************友情链接数据恢复区*************************************

    //显示待恢复的友情链接视图
    public function recoveryLinkList()
    {
        return view('admin@datarecoverycenter/recoverylinklist');

    }
    //处理待恢复的友情链接所需数据
    public function recoveryLinkQuery(){
        $count =\app\common\model\Link::onlyTrashed()->count();
        $limit = Request::instance()->param('limit')??$count;//获取limit参数，没有则为总数
        $page = Request::instance()->param('page')??1;//获取page参数，默认1（为了默认的第一次计算）
        $lname=Request::instance()->param('lname');//获取域名关键字
        if (isset($lname)){
            $lname=trim($lname);
            $sql="SELECT * FROM link WHERE delete_time is not null ";
            $sql.="AND lname like '"."%".$lname."%"."' limit ";
            $sql.=($page - 1) * $limit . ',' . $limit;
            $count=Db::name('link')
                ->where('lname','like',"%".$lname."%")
                ->where('delete_time is not null')
                ->count();
        }else{
            $sql = "SELECT * FROM link WHERE delete_time is not null ORDER BY lid desc LIMIT ";
            $sql .= ($page - 1) * $limit . ',' . $limit;
        }
        $data = db('link')->query($sql);
        return ['code' => 0, "msg" => "查询成功", 'count' => $count, 'data' => $data];
    }
    //还原友情链接
    public function recoveryLink(){
        $lid = Request::instance()->get('lid');
        $res = Db::name('link')->where('lid', $lid)->update(['delete_time' => null]);
        if ($res) {
            return ['code' => 1, 'msg' => '友情链接已还原~'];
        } else {
            return ['code' => 0, 'msg' => '还原失败，请稍后再试~'];
        }
    }
    //永久删除友情链接
    public function recoveryDel(){
        $lid = Request::instance()->get('lid');
        $res=Db::name('link')->where('lid',$lid)->delete();
        if ($res) {
            return ['code' => 1, 'msg' => '友情链接已永久性删除~'];
        } else {
            return ['code' => 0, 'msg' => '删除失败，请稍后再试~'];
        }
    }
}
