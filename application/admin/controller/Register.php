<?php

namespace app\admin\controller;

use app\common\model\Admins;use think\Controller;
use think\Request;

class Register extends Controller
{
     public function adminRegister(){
         return view('admin@Register/adminregister');
     }
     //判断注册帐号是否存在
    public function IsAdminExit(Request $request){
        $adminAccount=$request->post('adminAccount');
        $data=Admins::where('adminAccount',$adminAccount)->find();
        if ($data){
            return ['code'=>0,'msg'=>'帐号已存在!'];
        }
        else{
            return ['code'=>1,'msg'=>'帐号可用'];
        }
    }
    //注册逻辑
    public function getAdminRegisterData(Request $request){
         $adminName=$request->post('adminName');
        $adminAccount=$request->post('adminAccount');
        $password=$request->post('password');
        $captcha=$request->post('captcha');
         if (strtolower($captcha)!= session('captcha')) {
            return ['code' => 0, 'msg' => '验证码错误!'];
        }
         $res=Admins::create([
             'adminName'=>$adminName,
             'adminAccount'=>$adminAccount,
             'password'=>$password,
             'email'=>"xx@qq.com",
             'birthdate'=>'2000.1.1',
             'adminGender'=>'男',
             'adminPic'=>"static/index/images/common/默认头像女.jpg"
]);
         $admin=Admins::where('adminAccount',$adminAccount)->find();
            if ($res){
            session('admin',$admin);
            return json(['code'=>1,'msg'=>'注册成功!']);
        }else{
            return ['code'=>0,'msg'=>'注册失败!'];
        }
    }
}
