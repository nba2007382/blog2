<?php

namespace app\index\controller;
use app\common\model\Blogs;use think\Controller;
use think\Db;use think\Request;
class Index extends Controller
{
    //主页显示
    public function index()
    {
        $i=0;
        $j=0;
        $h=0;
        $link=$this->link();
        $cate=$this->catePaging();
        $hotBlog=$this->hotBlog();
        $getYearMonth=model('Blogs')->getYearMonth();
        return view('index@index/index',compact('i','j','h','link','cate','hotBlog','getYearMonth'));
    }
//博文列表流加载
public function article(Request $request){
        $page=$request->get('page');
        $pageSize=5;
        $count=Db::name('blogs')
                ->where('delete_time','null')
                ->count();
        $pages=ceil($count/$pageSize);
        $data=Db::name('blogs')
        ->where('delete_time','null')
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
    //友情链接
    public function link(){
     $data=Db::name('link')->where('delete_time','null')->select();
     return $data;
    }

    //热文Top10
    public function hotBlog(){
      $sql="SELECT * FROM `blogs` WHERE delete_time is null order by visited desc ,uploaddate desc limit 0,10";
      $data=db('blog')->query($sql);
      return $data;
    }

      //分类列表显示数据获取
    public function catePaging()
    {
            $sql="select * from blogtype WHERE delete_time is null";
            $arrs=db('blogtype')->query($sql);
            $data=$this->cate($arrs);
        return  $data;
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

}
