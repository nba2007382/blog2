<?php

namespace app\index\controller;

use think\Controller;use think\Db;use think\facade\Request;

class PersonalCenter extends Controller
{
   public function personalCenter(){
       //显示个人中心
         $index=new Index();
        $cate=$index->catePaging();
       return view('index@personalcenter/personalcenter',compact('cate'));
   }
   //我的资料
   public function myBase(){
       $data=session('user');
       $index=new Index();
        $cate=$index->catePaging();
       return view('index@personalcenter/mybase',compact('cate','data'));
   }
   //我的信息
   public function account(Request $request){
       $data=session('user');
          $index=new Index();
        $cate=$index->catePaging();
       return view('index@personalcenter/account',compact('cate','data'));
   }
   //我的头像
   public function listIcon(){
        $data=session('user');
          $index=new Index();
        $cate=$index->catePaging();
       return view('index@personalcenter/listicon',compact('cate','data'));
   }
   //我的点赞
   public function myPraise(){
      $data=session('user');
          $index=new Index();
        $cate=$index->catePaging();
        //获取点赞博文id
        $uid=session('user')['uid'];
        $arr_bid=Db::name('praise')->where('uid',$uid)->column('bid');
        //总数
        $count=count($arr_bid);
        //根据id获取博文列表
        $arr_blog=Db::name('blogs')->all($arr_bid);
       return view('index@personalcenter/mypraise',compact('cate','data','arr_blog','count'));
   }
   //我的留言
   public function myComments(){
       
       
             $data=session('user');
          $index=new Index();
        $cate=$index->catePaging();
       return view('index@personalcenter/mycomments',compact('cate','data'));
   }
   //我的收藏
   public function myLike(){
             $data=session('user');
          $index=new Index();
        $cate=$index->catePaging();
          //获取收藏博文id
          $uid=session('user')['uid'];
        $arr_bid=Db::name('like')->where('uid',$uid)->column('bid');
        //总数
        $count=count($arr_bid);
        //根据id获取博文列表
        $arr_blog=Db::name('blogs')->all($arr_bid);
       return view('index@personalcenter/mylike',compact('cate','data','arr_blog','count'));
   }

   //***********************获取所在地****************************
    public function city()
    {
        $type = Request::instance()->get('type');
        if ($type == 'province') {
            $sql = 'select * from province';
        } elseif ($type == 'city') {
            $Pcode = Request::instance()->get('Pcode');
            $sql = "select * from city where ProvinceCode =$Pcode";
        } elseif ($type == 'county') {
            $Ccode = Request::instance()->get('Ccode');
            $sql = "select * from county where CityCode = $Ccode";
        }
        $data = db('blogs')->query($sql);
        return $data;
    }
}
