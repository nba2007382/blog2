<?php

namespace app\admin\controller;

use app\admin\validate\UserValidate;
use app\common\model\Blogs;
use app\common\model\City;
use app\common\model\County;
use app\common\model\Province;
use app\common\model\Users;
use think\Controller;
use think\facade\Request;
use think\file\UploadedFile;

class User extends Controller
{
    //显示用户列表
    public function userList()
    {
        return view('admin@user/userlist');
    }

    //    删除用户
    public function userDel()
    {
        $uid = Request::get('uid');
        $res = db('users')->where('uid', $uid)->useSoftDelete('delete_time', time())->delete();
        if ($res){
            return json(['code'=>1,'msg'=>'删除成功']);
        }else{
            return json(['code'=>0,'msg'=>'删除失败']);
        }
    }

    //修改用户
    public function userEdit()
    {
        $uid = Request::instance()->get();
        $data = Users::find($uid);
        return view('admin@user/useredit', compact('data'));
    }

    /*  ---------------------------分割线----------------------------------*/
    //**********用户账号处理****---0封号--1正常
    public function editStatus()
    {
        $uid = Request::instance()->get('uid');
        $status = Request::instance()->get('status');
        if ($status == 0) {
            $res = Users::where('uid', $uid)->update(['status' => 1]);
            if ($res) {
                return json(['status' => 1, 'msg' => '账号已恢复正常']);
            } else {
                return json(['status' => 0, 'msg' => '账号解封失败']);
            }
        } else {
            $res = Users::where('uid', $uid)->update(['status' => 0]);
            if ($res) {
                return json(['status' => 0, 'msg' => '账号已停封']);
            } else {
                return json(['status' => 1, 'msg' => '封号失败']);
            }
        }
    }

    //处理修改用户
    public function doUserEdit()
    {
        $getData = Request::instance()->post();
        $uid = $getData['uid'];
        $res = $this->validate($getData, UserValidate::class);
        if (true !== $res) {
            return $this->error($res, "admin/user/userEdit?=".$uid, 1, 1);
        }
        //**********************上传图片*******************************
        //(1)判断上传图片是否有错误发生
        if ($_FILES['pic']['error'] != 0) {
            echo "<h2>上传图片有错误发生！</h2>";
            die();
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

        //(5)删除旧图片
        $filepic = Users::where('uid', $uid)->find();
        $filename = dirname(dirname(dirname(__DIR__))) . '/public/' . $filepic['pic'];
        if (file_exists($filename)) {
            unlink($filename);
        }
        //***********************将表单提交数据保存到数据库****************************
        $uname = trim($getData['uname']);
        $password = $getData['password'];
        $email = $getData['email'];
        $birthdate = $getData['birthdate'];
        $gender = $getData['gender'];
        $profession = $getData['profession'];
        $hobby = implode('、', $getData['hobby']);

        $province = $getData['province'];
        $city = $getData['city'];
        $county = $getData['county'];

        $p = Province::where('Pcode', $province)->find();
        $province = $p['Pname'];
        $c = City::where('Ccode', $city)->find();
        $city = $c['Cname'];
        $o = County::where('Acode', $county)->find();
        $county = $o['Aname'];

        $intro = $getData['intro'];
        $imgsrc = $dst_name; //将图片路径保存到数据库
        $sql = "update users set ";
        $sql .= "uname='$uname',password='$password',email='$email',birthdate='$birthdate', ";
        $sql .= "gender='$gender',profession='$profession',hobby='$hobby',province='$province', ";
        $sql .= "city='$city',county='$county',intro='$intro',pic='$imgsrc' where uid='$uid'";
        $data = db('Users')->execute($sql);

        if ($data) {
            $this->success('修改成功', 'admin/user/userList', 0, 2);
        } else {
            $this->error('修改失败', 'admin/user/userList', 1, 2);
        }
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

