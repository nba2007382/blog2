<?php

namespace app\index\controller;

use app\common\model\Blogs;use app\common\model\Comments;use app\common\model\Users;use think\Controller;use think\Db;use think\Request;

class Show extends Controller
{
    //详情页
    public function show(Request $request){
        $bid=$request->get('bid');
        //更新游览量 +1
        Db::name('blogs')
        ->where('bid',$bid)
        ->update([
            'visited'=>Db::raw('visited+1')
         ]);
        $i=0;
        $j=0;
        $h=0;
        $index=new Index();
        $link=$index->link();
        $cate=$index->catePaging();
        $hotBlog=$index->hotBlog();
        $uuid=$this->uuid();
        $data=Blogs::where('bid',$bid)->find();
        //博文归类
         $getYearMonth=model('blogs')->getYearMonth();
        //评论数据
        $count=Comments::where('bid',$bid)->count();
        $comments=Comments::with('userComments')->where('bid',$bid)->group('cdate desc')->paginate(5,false,['query'=>request()->param() ]);
        $p=$request->get('page')??1;
        $c=($p-1)*5;
        $isPraise="static/index/images/common/赞.png";
        $isLike="static/index/images/common/未收藏.png";
        //处理点赞显示
         if (session('user')['uid']){
            $sqlPraise="select bid from praise where uid =".session('user')['uid'];
            $praise_bid=db('blog')->query($sqlPraise);
            $praiseBids=[];
            foreach ($praise_bid as $k=>$v){
                foreach ($praise_bid[$k] as $index=>$value){
                    array_push($praiseBids,$value);
                }
            }
            $isPraise=in_array($bid,$praiseBids)?"static/index/images/common/已点赞.png":"static/index/images/common/赞.png";
         //处理收藏显示
            $sqlLike="select bid from `like` where uid =".session('user')['uid'];
            $like_bid=db('blog')->query($sqlLike);
            $likeBids=[];
            foreach ($like_bid as $k=>$v){
                foreach ($like_bid[$k] as $index=>$value){
                    array_push($likeBids,$value);
                }
            }
            $isLike=in_array($bid,$likeBids)?"static/index/images/common/已收藏.png":"static/index/images/common/未收藏.png";
        }
         //获取当前ID的上一篇、下一篇
         $prevNext[]=model('blogs')->getNextBlog("bid<$bid","bid DESC");
         $prevNext[]=model('blogs')->getNextBlog("bid>$bid","bid ASC");
//         foreach ($prevNext as $a => $b){
//             $prevNexts[]=$b[0];
//         }
//         dump($prevNext);die();
              return view('index@show/show',compact('i','j','h','c','link','cate','hotBlog','uuid','data','count','comments','isPraise','isLike','getYearMonth','prevNext'));
    }
    //显示博主信息
    public function about(){
        $index=new Index();
        $cate=$index->catePaging();
        return view('index@show/about',compact('cate'));

    }
    //*************************分割*************************
    public function uuid(){    //可以指定前缀 8-4-4-4-12
    $str = md5(uniqid(mt_rand(), true));
    $uuid  = substr($str,0,8) . '-';
    $uuid .= substr($str,8,4) . '-';
    $uuid .= substr($str,12,4) . '-';
    $uuid .= substr($str,16,4) . '-';
    $uuid .= substr($str,20,12);
    return  $uuid;
}
//处理星级和留言
     public function assessAndComment(Request $request){
        $evaluate=$request->post('evaluate');
        $content=$request->post('content');
        $uid=$request->post('uid');
        $bid=$request->post('bid');
        switch ($evaluate){
        case '1':$evaluate='★';break;
        case '2':$evaluate='★★';break;
        case '3':$evaluate='★★★';break;
        case '4':$evaluate='★★★★';break;
        case '5':$evaluate='★★★★★';break;
}
        $sql="insert into comments values(0,'$uid',$bid,'$evaluate','$content',now())";
        $res=db('blog')->query($sql);
        if (!$res){
            return ['code'=>1,'msg'=>'留言成功!'];
        }else{
            return ['code'=>0,'msg'=>'留言失败!'];
        }
     }
}
