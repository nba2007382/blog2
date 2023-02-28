<?php

namespace app\index\controller;

use think\Controller;use think\Db;use think\Request;

class BlogList extends Controller
{
    public function blogList(Request $request){
        $i=0;
        $j=0;
        $h=0;
        $index=new Index();
        $link=$index->link();
        $cate=$index->catePaging();
        $hotBlog=$index->hotBlog();
        $tid=$request->get('tid');
        $getYearMonth=model('blogs')->getYearMonth();
        //年月获取
        $year=$request->get('year');
        $month=$request->get('month');
        return view('index@bloglist/bloglist',compact('i','j','h','link','cate','hotBlog','tid','getYearMonth','year','month'));
    }
    //获取导航分类数据
    public function GetNavData(Request $request){
       $tid=$request->get('tid')??1;
       $tidData=Db::name('blogtype')
       ->where('tid',$tid)
       ->select();
       if ($tidData[0]['typename']=='关于博主'){
         return ['code'=>1,'url'=>'about'];
       }
       //*************获取下级菜单**********
       $array=Db::name('blogtype')->select();
       $getTids=$this->get_all_child($array,(int)$tid);
       array_unshift($getTids,$tid);
       //***************
       $page=$request->get('page');
       $pageSize=5;
       $count=Db::name('blogs')
                ->where('delete_time','null')
                ->whereIn('tid',$getTids)
                ->count();
       $pages=ceil($count/$pageSize);
       $data=Db::name('blogs')
       ->where('delete_time','null')
       ->whereIn('tid',$getTids)
       ->order('top desc')
       ->order('uploaddate desc')
       ->page($page,$pageSize)
       ->select();
          if (session('user')['uid']){
            $sqlPraise="select bid from praise where uid =".session('user')['uid'];
            $praise_bid=db('blog')->query($sqlPraise);
            $sqlLike="select bid from `like` where uid =".session('user')['uid'];
            $like_bid=db('blog')->query($sqlLike);
            return ['data'=>$data,'pages'=>$pages,'praise_bid'=>$praise_bid,'like_bid'=>$like_bid];
        }
       return ['data'=>$data,'pages'=>$pages];

    }
        //递归获取所有的子菜单的ID
    function get_all_child($array,$tid){
        $arr = array();
        foreach($array as $v){
            if($v['sid'] == $tid){
                $arr[] = $v['tid'];
                 $this->get_all_child($array,$v['tid']);
            };
        };
        return $arr;
    }
    //获取年月数据
    public function qYearMonth(Request $request){
       $year=$request->get('year');
       $month=$request->get('month');
       $page=$request->get('page')??1;
       $pageSize=5;
       $count=model('blogs')->getCount($year,$month);
       //array_shift 删除数组中的第一个元素（red），并返回被删除的元素：
       $id=array_shift($count[0]);
       $pages=ceil($id/$pageSize);
       $data=model('blogs')->queryYearMonth($year,$month,$page,$pageSize);
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
