<?php

namespace app\admin\controller;

use app\admin\validate\BlogValidate;
use app\common\model\Blogs;
use app\common\model\Users;
use app\common\model\Waytype;
use think\Request;
use think\Controller;
use think\Db;
use think\Image;
class Blog extends Controller
{
//    显示博文列表
    public function blogList()
    {
        return view('admin@blog/bloglist');
    }
//    博文删除
    public function blogDel(int $bid)
    {
        $res=db('blogs')->where('bid',$bid)->useSoftDelete('delete_time',time())->delete();
        if ($res){
            return json(['code'=>1,'msg'=>'删除成功']);
        }else{
            return json(['code'=>0,'msg'=>'删除失败']);
        }
    }
    //添加博文页
    public function blogAdd(){
        $category=new Category();
        $waytype=Waytype::select();
        $cate_data=$category->categoryPaging();
        return view('admin@blog/blogadd',compact('cate_data','waytype'));
    }
    //修改博文
    public function blogEdit(Request $request){
        $bid=$request->get('bid');
        $data=Blogs::find($bid);
        $waytype=Waytype::select();
        $category=new Category();
        $cate_data=$category->categoryPaging();
        return view('admin@blog/blogedit',compact('data','waytype','cate_data'));
    }


    //********************分割线***********************************
    //置顶状态处理
    public function topEdit(Request $request){
        $bid=$request->get('bid');
        $code=$request->get('code');
        if ($code == 0) {
            $res = Blogs::where('bid', $bid)->update(['top' => 1]);
            if ($res) {
                return json(['code' => 1, 'msg' => '置顶成功!']);
            } else {
                return json(['code' => 0, 'msg' => '置顶失败!']);
            }
        } else {
            $res = Blogs::where('bid', $bid)->update(['top' => 0]);
            if ($res) {
                return json(['code' => 1, 'msg' => '已取消置顶!']);
            } else {
                return json(['code' => 0, 'msg' => '取消置顶失败!']);
            }
        }
    }
    //处理修改博文操作
    public function doEditBlog(Request $request){
        $data=$request->post('data');
         if ($data==''){
            return json(['status'=>0,'msg'=>'您尚未有任何修改！']);
        }
         $img=$request->post('img');
        $intro=$data['intro'];
        $detail=$data['detail'];
        $title=$data['title'];
        $tid=$data['tid'];
        $wid=$data['wid'];
        $address=$data['address']??'';
        $bid=$data['bid'];
        $top=$data['top']??0;
        $uploadadmin=session('admin')['adminName'];
        if (!isset($address)){
      $sql="update blogs set title='$title',tid='$tid',wid='$wid',uploadadmin='$uploadadmin',intro='$intro',detail='$detail',top='$top',pic='$img',uploaddate=now() where bid='$bid'";
  }else{
      $sql="update blogs set title='$title',tid='$tid',wid='$wid',uploadadmin='$uploadadmin',address='$address',top='$top',pic='$img',intro='$intro',detail='$detail',uploaddate=now() where bid='$bid'";
  }
 if (!$img){
      $filepic=Blogs::where('bid',$bid)->find();
  $filename=dirname(dirname(dirname(__DIR__))).'/public/'.$filepic['pic'];
        if (file_exists($filename)){
            unlink($filename);
        }
 }
            $res=db('blog')->execute($sql);
            if ($res){
                return json(['status'=>1,'msg'=>'修改成功！']);
            }else{
                return json(['status'=>0,'msg'=>'修改失败！']);

            }
    }

    //处理添加博文操作
    public function doAddBlog(Request $request){
          $data=$request->request('data');
        $img=$request->post('img');
        $intro=$data['intro'];
        $detail=$data['detail'];
        $top=$data['top']??0;
        $address=$data['address']??'null';
          $title=$data['title'];
          $tid=$data['tid'];
          $wid=$data['wid'];
          $uploadadmin=session('admin')['adminName'];
          $sql="insert into blogs values(0,'$title','$tid','$wid','$intro','$detail','$uploadadmin',NOW(),0,0,0,'$address','$img',$top,null)";
          $res=db('blog')->execute($sql);
          if ($res){
              return json(['status'=>1,'msg'=>'添加成功！']);
          }else{
              return json(['status'=>0,'msg'=>'添加失败！']);
          }
    }

    //************图片区*************************
    // 文件上传
    public function upload(Request $request) {
        // 获取表单上传文件 例如上传了001.jpg
        $file = request()->file('pic');
        // 移动到框架应用根目录/uploads/ 目录下
        $info = $file->move('./uploads');
        if ($info) {
            $savename = '/uploads/'.str_replace('\\','/',$info->getSaveName());
            // 打开图片
            $image = Image::open(public_path().$savename);
            // 生成缩略图
            $image->thumb(150,150)->save(public_path().$savename);
            return json(['status' => 0, 'msg' => $savename]);
        } else {
            return json(['status' => 1, 'msg' => $file->getError()]);
        }
    }

    // 删除文件
    public function picdel(Request $request) {
        // 注意路径问题
        $img = dirname(dirname(dirname(__DIR__))).'/public'.$request->delete('img');
        if (unlink($img)){
            $data = ['status'=>0,'msg'=>'删除成功'];
        }else{
            $data = ['status'=>1,'msg'=>'删除失改'];
        }
        return json($data);
    }

}
