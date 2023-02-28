<?php
use think\facade\Route;

//前台路由
//0.登录/注册/注销
//*********登录*****************
Route::get('login','@index/Login/login')->name('index/Login/login');
Route::post('getLoginData','@index/Login/getLoginData')->name('index/Login/getLoginData');
//*********注册*****************
Route::get('register','@index/Register/register')->name('index/Register/register');
Route::post('getRegisterData','@index/Register/getRegisterData')->name('index/Register/getRegisterData');
Route::post('getAccountData','@index/Register/getAccountData')->name('index/Register/getAccountData');
//*********注销*****************
Route::get('userLoginOut','@index/LoginOut/userLoginOut')->name('index/LoginOut/userLoginOut');
//*********点赞/更新*****************
Route::post('updatePraise','@index/PraiseAndLike/updatePraise')->name('index/PraiseAndLike/updatePraise');
Route::post('updateLike','@index/PraiseAndLike/updateLike')->name('index/PraiseAndLike/updateLike');

//1.主页
Route::get('index','@index/Index/index')->name('index/Index/index');
Route::get('link','@index/Index/link')->name('index/Index/link');
Route::get('catepaging','@index/Index/catePaging')->name('index/Index/catePaging');
Route::get('hotblog','@index/Index/hotBlog')->name('index/Index/hotBlog');
Route::get('article','@index/Index/article')->name('index/Index/article');

//2.博文列表
Route::get('bloglist','@index/BlogList/blogList')->name('index/BlogList/blogList');
Route::get('getnavdata','@index/BlogList/GetNavData')->name('index/BlogList/GetNavData');
Route::get('qYearMonth','@index/BlogList/qYearMonth')->name('index/BlogList/qYearMonth');

//3.搜索页
Route::get('search','@index/Search/search')->name('index/Search/search');
Route::get('searchlist','@index/Search/searchList')->name('index/Search/searchList');

//4.详情页
Route::get('show','@index/Show/show')->name('index/Show/show');
Route::get('uuid','@index/Show/uuid')->name('index/Show/uuid');
Route::get('about','@index/Show/about')->name('index/Show/about');
Route::post('assessAndComment','@index/Show/assessAndComment')->name('index/Show/assessAndComment');
Route::group(['middleware' => ['CheckQtLogin']], function () {
//5.个人中心
Route::get('personalCenter','@index/PersonalCenter/personalCenter')->name('index/PersonalCenter/personalCenter');
//我的资料
Route::get('myBase','@index/PersonalCenter/myBase')->name('index/PersonalCenter/myBase');
//我的信息
Route::get('account','@index/PersonalCenter/account')->name('index/PersonalCenter/account');
//我的头像
Route::get('listicon','@index/PersonalCenter/listIcon')->name('index/PersonalCenter/listIcon');
//我的点赞
Route::get('mypraise','@index/PersonalCenter/myPraise')->name('index/PersonalCenter/myPraise');
//我的收藏
Route::get('mylike','@index/PersonalCenter/myLike')->name('index/PersonalCenter/myLike');
//我的留言
Route::get('mycomments','@index/PersonalCenter/myComments')->name('index/PersonalCenter/myComments');
//获取所在地
Route::get('city','@index/PersonalCenter/city')->name('index/PersonalCenter/city');

//处理我的信息提交数据
Route::post('getAccount','@index/PersonalCenterHandler/getAccount')->name('index/PersonalCenterHandlerr/getAccount');
//处理头像修改
Route::post('updateIcon','@index/PersonalCenterHandler/updateIcon')->name('index/PersonalCenterHandlerr/updateIcon');
//处理上传头像
Route::post('qtUpload','@index/PersonalCenterHandler/qtUpload')->name('index/PersonalCenterHandlerr/qtUpload');
//删除头像
Route::delete('qtPicDel','@index/PersonalCenterHandler/qtPicDel')->name('index/PersonalCenterHandlerr/qtPicDel');
//处理我的点赞
Route::get('praiseStatus','@index/PersonalCenterHandler/praiseStatus')->name('index/PersonalCenterHandlerr/praiseStatus');
//处理我的收藏
Route::get('likeStatus','@index/PersonalCenterHandler/likeStatus')->name('index/PersonalCenterHandlerr/likeStatus');
//处理我的留言显示
Route::get('myCommentListQuery','@index/PersonalCenterHandler/myCommentListQuery')->name('index/PersonalCenterHandlerr/myCommentListQuery');
//删除我的留言
Route::get('myCommentDel','@index/PersonalCenterHandler/myCommentDel')->name('index/PersonalCenterHandlerr/myCommentDel');
});

//后台路由
//0.登录/注销模块
Route::get('adminlogin', '@admin/Login/login')->name('adminlogin');
Route::post('loginhandler', '@admin/Login/loginHandler')->name('admin/Login/loginHandler');
Route::get('loginout', '@admin/Login/loginOut')->name('admin/Login/loginOut');
Route::get('captcha', '@admin/Login/captcha')->name('admin/Login/capthca');
Route::get('personaldata', '@admin/Login/personalData')->name('admin/Login/personalData');
Route::post('dopersonaldata', '@admin/Login/doPersonalData')->name('admin/Login/doPersonalData');

//+1注册
Route::get('adminRegister', '@admin/Register/adminRegister')->name('adminRegister');
Route::post('IsAdminExit', '@admin/Register/IsAdminExit')->name('IsAdminExit');
Route::post('getAdminRegisterData', '@admin/Register/getAdminRegisterData')->name('getAdminRegisterData');

//登录权限starting
Route::group(['middleware' => ['CheckLogin']], function () {

//1.博文模块
    Route::get('adminbloglist', '@admin/blog/blogList')->name('admin/blog/blogList');
    Route::get('blogquery', '@admin/BlogPaging/query')->name('admin/BlogPaging/query');
    Route::get('blogdel', '@admin/blog/blogDel')->name('admin/blog/blogDel');
    Route::get('blogadd', '@admin/blog/blogAdd')->name('admin/blog/blogAdd');
    Route::get('topedit', '@admin/blog/topEdit')->name('admin/blog/topEdit');
    Route::post('doaddblog', '@admin/blog/doAddBlog')->name('admin/blog/doAddBlog');
    Route::get('blogedit', '@admin/blog/blogEdit')->name('admin/blog/blogEdit');
    Route::post('upload', '@admin/blog/upload')->name('admin/blog/upload');
    Route::delete('picdel', '@admin/blog/picDel')->name('admin/blog/picDel');
    Route::post('doeditblog', '@admin/blog/doEditBlog')->name('admin/blog/doEditBlog');

//2.栏目模块
    Route::get('categorylist', '@admin/category/categoryList')->name('admin/category/categoryList');
    Route::get('categorypaging', '@admin/category/categoryPaging')->name('admin/category/categoryPaging');
    Route::get('cate', '@admin/category/cate')->name('admin/category/cate');
    Route::get('delcate', '@admin/category/delCate')->name('admin/category/delCate');
    Route::get('child', '@admin/category/get_all_child')->name('admin/category/get_all_child');
    Route::get('categoryadd', '@admin/category/categoryAdd')->name('admin/category/categoryAdd');
    Route::post('docategoryadd', '@admin/category/doCategoryAdd')->name('admin/category/doCategoryAdd');
    Route::get('categoryedit', '@admin/category/categoryEdit')->name('admin/category/categoryEdit');
    Route::post('docategoryedit', '@admin/category/doCategoryEdit')->name('admin/category/doCategoryEdit');


//3.用户模块
    Route::get('userlist', '@admin/user/userList')->name('admin/user/userList');
    Route::get('userquery', '@admin/UserPaging/query')->name('admin/UserPaging/query');
    Route::get('userdel', '@admin/user/userDel')->name('admin/user/userDel');
    Route::get('useredit', '@admin/user/userEdit')->name('admin/user/userEdit');
    Route::post('douseredit', '@admin/user/doUserEdit')->name('admin/user/doUserEdit');
    Route::get('editstatus', '@admin/user/editStatus')->name('admin/user/editStatus');
    Route::get('admincity', '@admin/user/city')->name('admin/user/admincity');

//4.友情链接模块
    Route::get('linklist', '@admin/link/linkList')->name('admin/link/linkList');
    Route::get('linkshow', '@admin/link/linkShow')->name('admin/link/linkShow');
    Route::get('linkdel', '@admin/link/linkDel')->name('admin/link/linkDel');
    Route::get('linkadd', '@admin/link/linkAdd')->name('admin/link/linkAdd');
    Route::post('dolinkadd', '@admin/link/doLinkAdd')->name('admin/link/doLinkAdd');
    Route::get('linkedit', '@admin/link/linkEdit')->name('admin/link/linkEdit');
    Route::post('dolinkedit', '@admin/link/doLinkEdit')->name('admin/doLinkEdit');

//5.评论模块
    Route::get('commentlist', '@admin/Comment/commentList')->name('admin/Comment/commentList');
    Route::get('commentlistquery', '@admin/Comment/commentListQuery')->name('admin/Comment/commentListQuery');
    Route::get('commentdel', '@admin/Comment/commentDel')->name('admin/Comment/commentDel');

//6.数据恢复中心模块
//用户
    Route::get('recoveryuserlist', '@admin/DataRecoveryCenter/recoveryUserList')->name('admin/DataRecoveryCenter/recoveryUserList');
    Route::get('recoveryuserquery', '@admin/DataRecoveryCenter/recoveryUserQuery')->name('admin/DataRecoveryCenter/recoveryUserQuery');
    Route::get('recoveryuser', '@admin/DataRecoveryCenter/recoveryUser')->name('admin/DataRecoveryCenter/recoveryUser');
    Route::get('recoveryuserdel', '@admin/DataRecoveryCenter/recoveryUserDel')->name('admin/DataRecoveryCenter/recoveryUserDel');

//博客
    Route::get('recoverybloglist', '@admin/DataRecoveryCenter/recoveryBlogList')->name('admin/DataRecoveryCenter/recoveryBlogList');
    Route::get('recoveryblogquery', '@admin/DataRecoveryCenter/recoveryBlogQuery')->name('admin/DataRecoveryCenter/recoveryBlogQuery');
    Route::get('recoveryblog', '@admin/DataRecoveryCenter/recoveryBlog')->name('admin/DataRecoveryCenter/recoveryBlog');
    Route::get('recoveryblogdel', '@admin/DataRecoveryCenter/recoveryBlogDel')->name('admin/DataRecoveryCenter/recoveryBlogDel');

//友情链接
    Route::get('recoverylinklist', '@admin/DataRecoveryCenter/recoveryLinkList')->name('admin/DataRecoveryCenter/recoveryLinkList');
    Route::get('recoverylinkquery', '@admin/DataRecoveryCenter/recoveryLinkQuery')->name('admin/DataRecoveryCenter/recoveryLinkQuery');
    Route::get('recoverylink', '@admin/DataRecoveryCenter/recoveryLink')->name('admin/DataRecoveryCenter/recoveryLink');
    Route::get('recoverydel', '@admin/DataRecoveryCenter/recoveryDel')->name('admin/DataRecoveryCenter/recoveryDel');

//博客分类
    Route::get('recoveryblogtypelist', '@admin/DataRecoveryCenter/recoveryBlogTypeList')->name('admin/DataRecoveryCenter/recoveryBlogTypeList');
    Route::get('recoveryblogtypequery', '@admin/DataRecoveryCenter/recoveryBlogtypeQuery')->name('admin/DataRecoveryCenter/recoveryBlogtypeQuery');
    Route::get('recoveryblogtype', '@admin/DataRecoveryCenter/recoveryBlogtype')->name('admin/DataRecoveryCenter/recoveryBlogtype');
    Route::get('recoveryblogtypedel', '@admin/DataRecoveryCenter/recoveryBlogtypeDel')->name('admin/DataRecoveryCenter/recoveryBlogtypeDel');

});
//登录权限ending
