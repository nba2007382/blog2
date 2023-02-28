<?php

namespace app\index\controller;

use app\common\model\Blogs;use app\common\model\Like;use app\common\model\Praise;use think\Controller;use think\Db;use think\Request;

class PraiseAndLike extends Controller
{
    //点赞更新
    public function updatePraise(Request $request){
     $bid=$request->post('bid');
     $uid=$request->post('uid');
     $ip=$_SERVER["REMOTE_ADDR"];
     $sel=Praise::where('bid',$bid)
     ->where('uid',$uid)
     ->find();
      if ($sel){
         Db::name('praise')
         ->where('bid',$bid)
         ->where('uid',$uid)
         ->delete();
         Db::name('blogs')
         ->where('bid',$bid)
         ->update([
           'praise'=>Db::raw('praise-1')
]);
         $sum=Blogs::where('bid',$bid)->value('praise');
         return ['code'=>0,'msg'=>'取消点赞!','sum'=>$sum];
      }else{
          $sql="insert into praise values (0,'$uid','$bid',1,NOW(),'$ip')";
          db('blog')->query($sql);
          Db::name('blogs')
         ->where('bid',$bid)
         ->update([
           'praise'=>Db::raw('praise+1')
]);
          $sum=Blogs::where('bid',$bid)->value('praise');
          return ['code'=>1,'msg'=>'点赞成功!','sum'=>$sum];
      }

    }
    //收藏更新
    public function updateLike(Request $request){
        $bid=$request->post('bid');
      $uid=$request->post('uid');
       $ip=$_SERVER["REMOTE_ADDR"];
       $sel=Like::where('bid',$bid)
           ->where('uid',$uid)
     ->find();
        if ($sel){
         Db::name('`like`')
         ->where('bid',$bid)
         ->where('uid',$uid)
         ->delete();
         Db::name('blogs')
         ->where('bid',$bid)
         ->update([
           'like'=>Db::raw('`like`-1')
]);
         $sum=Blogs::where('bid',$bid)->value('`like`');
         return ['code'=>0,'msg'=>'取消点赞!','sum'=>$sum];
      }else{
          $sql="insert into `like` values (0,'$uid','$bid',1,NOW(),'$ip')";
          db('blog')->query($sql);
          Db::name('blogs')
         ->where('bid',$bid)
         ->update([
           'like'=>Db::raw('`like`+1')
]);
          $sum=Blogs::where('bid',$bid)->value('`like`');
          return ['code'=>1,'msg'=>'点赞成功!','sum'=>$sum];
      }
    }
}
