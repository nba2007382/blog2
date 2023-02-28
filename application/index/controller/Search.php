<?php

namespace app\index\controller;

use app\common\model\Blogs;use app\common\model\Link;use http\Message\Body;use think\Controller;use think\Db;use think\Request;

class Search extends Controller
{
    public function searchList(){
        $i=0;
        $j=0;
        $h=0;
        $index=new Index();
        $link=$index->link();
        $cate=$index->catePaging();
        $hotBlog=$index->hotBlog();
        $getYearMonth=model('blogs')->getYearMonth();
        return view('index@search/searchlist',compact('i','j','h','link','cate','hotBlog','key','getYearMonth'));
    }
    public function search(Request $request){
        $key=$request->get('key');
        $page=$request->get('page');
        $pageSize=5;
        $blogs=new Blogs();
        $count=$blogs->getSearchCount($key);
        $data=$blogs->getSearchData($key,$page,$pageSize);
        $pages=ceil($count/$pageSize);
         if (session('user')['uid']){
            $sqlPraise="select bid from praise where uid =".session('user')['uid'];
            $praise_bid=db('blog')->query($sqlPraise);
            $sqlLike="select bid from `like` where uid =".session('user')['uid'];
            $like_bid=db('blog')->query($sqlLike);
            return ['data'=>$data,'pages'=>$pages,'praise_bid'=>$praise_bid,'like_bid'=>$like_bid];
        }
        return ['data'=>$data,'pages'=>$pages];
    }
}
