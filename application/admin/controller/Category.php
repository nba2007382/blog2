<?php

namespace app\admin\controller;

use app\common\model\Blogtype;
use app\common\model\Waytype;
use Psr\Log\Test\DummyTest;
use think\Controller;
use think\Db;
use think\facade\Request;
use think\facade\Route;

class Category extends Controller
{
    //显示视图
    public function categoryList()
    {
        return view('admin@category/categorylist');
    }
    //修改类别
    public function categoryEdit(){
        $tid=Request::instance()->get('tid');
        $data=Blogtype::where('tid',$tid)->find();
        return view('admin@category/categoryedit',compact('data'));
    }
    //添加分类
    public function categoryAdd(){
        $category=new Category();
        $cate_data=$category->categoryPaging();
        return view('admin@category/categortadd',compact('cate_data'));
    }
//**********************     分割线      ***********************/
//添加类别
public function doCategoryAdd(){
//        $res=Blogtype::create(Request::post());
    $res=Db::name('blogtype')->insert(Request::post());
        if ($res){
            return ['code'=>1,'msg'=>'添加成功！'];

        }else{
            return ['code'=>0,'msg'=>'添加失败！'];
        }
}
//修改类别
public function doCategoryEdit(){
        $res=Blogtype::update(Request::post());
        if ($res){
            return ['code'=>1,'msg'=>'更新成功！'];
        }else{
            return ['code'=>0,'msg'=>'更新失败！'];
        }
}

    //分类列表显示数据获取
    public function categoryPaging()
    {
        $count = Blogtype::count();
       $typename=Request::get('typename');//获取类别关键字
        if (isset($typename)){
            $typename=trim($typename);
            $sql="select *from blogtype where delete_time is null AND ";
            $sql.="typename like '"."%".$typename."%"."'";
            $count=Db::name('blogtype')
                ->where('typename','like',"%".$typename."%")
                ->count();
            $data=db('blog')->query($sql);

        }else{
            $sql="select * from blogtype WHERE delete_time is null";
            $arrs=db('blogtype')->query($sql);
            $data=$this->cate($arrs);
        }
        return ["code" => "0", "sql"=>$sql,"typename"=>$typename,"msg" => "查询成功", "count" => $count, "data" => $data];
    }
    //获取无限级分类
    public function cate($arrs,$level=0,$sid=0)	{
        static $categorys = array();
        //循环原始的分类数组
        foreach($arrs as $arr)
        {
            //查找下级菜单
            if($arr['sid']==$sid)
            {
                $arr['level'] = $level;
                $arr['html']=str_repeat('---',$level*4);
                $categorys[] = $arr;
                //递归调用
                $this->cate($arrs,$level+1,$arr['tid']);
            }
        }
        //返回结果
        return $categorys;
    }
    //递归获取所有的子菜单的ID
    function get_all_child($array,$tid){
        $arr = array();
        foreach($array as $v){
            if($v['sid'] === $tid){
                $arr[] = $v['tid'];
                 $this->get_all_child($array,$v['tid']);
            };
        };
        return $arr;
    }
    //删除无限级分类
    public function delCate(){
        $getTid=Request::instance()->get('tid');
        $array=Db::name('blogtype')->select();
        $delTid=$this->get_all_child($array,(int)$getTid);
        $delTid[]=array_push($delTid,$getTid);
        $res=Blogtype::destroy($delTid);
        if ($res){
            return json(['code'=>1,'msg'=>'删除成功']);
        }else{
            return json(['code'=>0,'msg'=>'删除失败']);
        }
    }

}
