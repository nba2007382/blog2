<?php

namespace app\index\controller;

use app\common\model\City;use app\common\model\Comments;use app\common\model\County;use app\common\model\Province;use think\Controller;use think\Db;use think\Image;use think\Request;

class PersonalCenterHandler extends Controller
{
    //处理个人信息修改
    public function getAccount(Request $request){
        $getData=$request->post();
        $uid=$getData['data']['uid'];
        $uname = trim($getData['data']['uname']);
        $email = $getData['data']['email'];
        $intro = $getData['data']['intro'];
        $birthdate = $getData['data']['birthdate'];
        $gender = $getData['data']['gender'];
        $profession = $getData['data']['profession']??'';
        $hobby = $getData['checkBox']??'';
        if ($hobby==''){
            return ['code'=>0,'msg'=>'爱好未选!'];
        }
        $hobby = implode('、', $getData['checkBox']);
        $province = $getData['data']['province'];
        $city = $getData['data']['city'];
        $county = $getData['data']['county'];
        if ($profession==''){
            return ['code'=>0,'msg'=>'职业未选!'];
        }
        if ($province==0){
            return ['code'=>0,'msg'=>'省份未选!'];
        }
        if ($city==0){
            return ['code'=>0,'msg'=>'城市未选!'];
        }
        if ($county==0){
            return ['code'=>0,'msg'=>'县/区未选!'];
        }
        $p = Province::where('Pcode', $province)->find();
        $province = $p['Pname'];
        $c = City::where('Ccode', $city)->find();
        $city = $c['Cname'];
        $o = County::where('Acode', $county)->find();
        $county = $o['Aname'];
        $sql = "update users set ";
        $sql .= "uname='$uname',email='$email',birthdate='$birthdate', ";
        $sql .= "gender='$gender',profession='$profession',hobby='$hobby',province='$province', ";
        $sql .= "city='$city',county='$county',intro='$intro' where uid='$uid'";
        $data = db('Users')->execute($sql);
        if ($data){
            return ['code'=>1,'msg'=>'修改成功!'];
        }else{
            return ['code'=>0,'msg'=>'修改失败!'];
        }
    }
    //处理个人头像修改
    public function updateIcon(Request $request){
        $img=$request->post('img');
        $uid=$request->post('uid');
        $res=Db::name('users')
        ->where('uid',$uid)
        ->update([
         'pic'=>$img
]);
        if ($res){
            session(null);
            $user=Db::name('users')
            ->where('uid',$uid)
            ->find();
            session('user',$user);
            return ['code'=>1,'msg'=>'更新成功!','img'=>$img];
        }else{
            return ['code'=>0,'msg'=>'更新失败!'];
        }
    }
    //处理我的点赞
    public function praiseStatus(Request $request){
        $bid=$request->get('bid');
        $uid=session('user')['uid'];
        $res=Db::name('praise')
        ->where('uid',$uid)
        ->where('bid',$bid)
        ->delete();
        if ($res){
            return ['code'=>1,'msg'=>'已取消此点赞!'];
        }else{
            return ['code'=>0,'msg'=>'取消失败,请稍后再试!'];
        }
    }
    //处理我的收藏
     public function likeStatus(Request $request){
        $bid=$request->get('bid');
        $uid=session('user')['uid'];
        $res=Db::name('like')
        ->where('uid',$uid)
        ->where('bid',$bid)
        ->delete();
        if ($res){
            return ['code'=>1,'msg'=>'已取消此收藏!'];
        }else{
            return ['code'=>0,'msg'=>'取消失败,请稍后再试!'];
        }
    }
    //********************分割**********************
    //处理头像上传
        public function qtUpload(Request $request) {
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
            return json(['code' => 1, 'msg' => $savename]);
        } else {
            return json(['code' => 0, 'msg' => $file->getError()]);
        }
    }

    // 删除头像上传文件
    public function qtPicDel(Request $request) {
        // 注意路径问题
        $img = dirname(dirname(dirname(__DIR__))).'/public'.$request->delete('img');
        if (unlink($img)){
            $data = ['code'=>1,'msg'=>'删除成功'];
        }else{
            $data = ['code'=>0,'msg'=>'删除失改'];
        }
        return json($data);
    }

    //显示留言列表数据
        public function myCommentListQuery(Request $request)
    {
        $uid=session('user')['uid'];
        $count = Comments::where('uid',$uid)->count();
        $limit = $request->param('limit')??$count;//获取limit参数，没有则为总数
        $page =$request->param('page')??1;//获取page参数，默认1（为了第一次计算）
        $content=$request->param('content');
        if (isset($content)){
            $sql="SELECT comments.*,blogs.title,blogs.intro,blogs.pic FROM comments ";
            $sql.="LEFT JOIN blogs on comments.bid=blogs.bid ";
            $sql.="where uid=".$uid;
            $sql.=" and content like "."'%".$content."%' ";
            $sql.="ORDER BY cid DESC ";
            $sql.="LIMIT ". ($page - 1) * $limit . ',' . $limit;
            $count=Db::name('comments')
                ->where('uid',$uid)
                ->where('content','like',"%".$content."%")
                ->count();

        }else{
            $sql="SELECT comments.*,blogs.title,blogs.intro,blogs.pic FROM comments ";
            $sql.="LEFT JOIN blogs on comments.bid=blogs.bid ";
            $sql.="where uid=".$uid;
            $sql.=" ORDER BY cid DESC ";
            $sql.="LIMIT ". ($page - 1) * $limit . ',' . $limit;
        }
        $data=db('blog')->query($sql);
        if ($data){
                    return ["code" => "0", "msg" => "查询成功", "count" => $count, "data" => $data];
        }else{
                    return ["code" => "1", "msg" => "查询失败",  "sql" => $sql];
        }
    }

     //删除评论
    public function myCommentDel(Request $request)
    {
       $cid=$request->get('cid');
       $res=Comments::where('cid',$cid)->delete();
        if ($res) {
            return ['code' => 1, 'msg' => '评论已删除~'];
        } else {
            return ['code' => 0, 'msg' => '删除失败，请稍后再试~'];
        }
    }


}
