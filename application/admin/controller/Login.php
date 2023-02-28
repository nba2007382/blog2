<?php

namespace app\admin\controller;

use app\common\controller\Captcha;
use app\common\model\Admins;
use app\common\model\Users;
use think\Controller;
use think\Db;
use think\Request;

class Login extends Controller
{
    //显示登录视图
    public function login()
    {
        return view('admin@login/login');
    }

    //登录处理
    public function loginHandler(Request $request)
    {
        $data = $request->post();
        if (strtolower($data['captcha'] )!= session('captcha')) {
            return ['code' => 0, 'msg' => '验证码错误!'];
        }
        $res = Admins::where('adminaccount', $data['adminaccount'])->find();
        if (!$res) {
            return ['code' => 0, 'msg' => '帐号错误!'];
        }
        if (md5($data['password']) != md5($res['password'])) {
            return ['code' => 0, 'msg' => '密码错误!'];
        }
        $update = Db::name('admins')->where('adminAccount', $data['adminaccount'])->update([
            'last_IP' => $_SERVER['REMOTE_ADDR'],
            'times' => $res['times'] + 1
        ]);
        session('admin', $res);
        return ['code'=>1,'msg'=>'登录成功!'];
    }

    //获取验证码
    public function captcha()
    {
        $captcha = new Captcha();
        session('captcha', $captcha->getCode());
    }

    //注销
    public function loginOut()
    {
        session(null);
        return redirect(url('adminlogin'));
    }

    //我的个人资料
    public function personalData()
    {
        $taken=$this->request->token('__token__','personaldata');
        session('token',$taken);
        return view('admin@login/personaldata',compact('token'));
    }

    //处理修改个人资料
    public function doPersonalData(Request $request)
    {
        $data=$request->post();
        $adminid=$data['adminid'];
        if (!session('token')==$data['__token__']){
          return ['code'=>0,'msg'=>'已过期，请重新刷新页面后保存!'];
        }
        unset($data['__token__']);
        unset($data['pwd_again']);
        if (empty($data['password'])){
            unset($data['password']);
        }
        //图片没上传的操作
        if ($_FILES['pic']['error'] ==4) {
            $res=Admins::update($data);
            if ($res) {
                $this->success('修改成功', 'admin/Login/login', 0, 2);
            } else {
                $this->error('修改失败', 'admin/Login/login', 1, 2);
            }
        }
        //(2)判断上传文件内容类型是不是图片
        $arr1 = array("image/jpeg", "image/png", "image/gif");
        //创建finfo的资源：获取文件内容类型，与扩展名无关
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        //获取文件内容的原始类型，不会随着扩展名改名而改变
        $mime = finfo_file($finfo, $_FILES['pic']['tmp_name']);
        if (!in_array($mime, $arr1)) {
            echo "<h2>上传的必须是图像！</h2>";
            die();
        }

        //(3)判断上传的文件扩展名是不是图片
        $arr2 = array("jpg", "gif", "png");
        $ext = pathinfo($_FILES['pic']['name'], PATHINFO_EXTENSION); //文件扩展名
        if (!in_array($ext, $arr2)) {
            echo "<h2>上传的必须是图像！</h2>";
            die();
        }

        //(4)移动图片到 images目录中
        $tmp_name = $_FILES['pic']['tmp_name'];
        $dst_name = "static/admin/upload/" . uniqid() . "." . $ext;
        move_uploaded_file($tmp_name, $dst_name);

//        (5)删除旧图片
        $filepic = Admins::where('adminid', $adminid)->find();
        $filename = dirname(dirname(dirname(__DIR__))) . '/public/' . $filepic['adminPic'];
        if (file_exists($filename)) {
            unlink($filename);
        }
        $imgsrc = $dst_name; //将图片路径保存到数据库
        $res=Admins::where('adminid',$adminid)->update(['adminPic'=>$imgsrc]);
        $ret=Admins::update($data);
        if ($res||$ret) {
            $this->redirect('adminlogin');
        } else {
            $this->error('修改失败', 'adminlogin', 1, 2);
        }

    }
    //ending
}
