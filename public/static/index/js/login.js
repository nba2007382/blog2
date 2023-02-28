var mySwiper = new Swiper('.swiper-container', {
    loop: true,
    direction: 'vertical', //2.Slides的滑动方向，可设置水平(horizontal)或垂直(vertical)；
    effect: 'coverflow',
    initialSlide: 1, //3.初始化显示的swiper-slide，从下标为0的swiper-slide开始，默认为0；
    speed: 25000, //4.切换速度，即slider自动滑动开始到结束的时间（单位ms），也是触摸滑动时释放至贴合的时间,默认300。
    width: 500,
    height: 852, //5.制Swiper的高/宽度(px)，当你的Swiper在隐藏状态下初始化时且切换方向为垂直才用得上。这个参数会使自适应失效。
    autoplay: { //8.自动播放 
        delay: 1000, //8.1自动播放间隔时间
        stopOnLastSlide: false, //8.2切换到最后一个是否停止，但是在loop:true下无效果；
        disableOnInteraction: false, //8.3用户触碰,悬停，拖放是否自动播放停止，默认为true;
        reverseDirection: false, //8.4 是否开启反向轮播，默认为false
    }
})
//验证码
$(function () {
    $('#captcha').click(function () {
        $('#captcha').attr('src', 'captcha?' + Math.random())
    })
});

//登录表单提交
layui.use('layer', function () {
    var layer = layui.layer;
    $('.submit').on('click', function () {
        let account = $(" input[ name='account' ] ").val();
        let password = $(" input[ name='password' ] ").val();
        let captcha = $(" input[ name='captcha' ] ").val();
        if (account.trim() == '') {
            layer.msg('帐号未填!', function () {
                return false;
            });
        } else if (password.trim() == '') {
            layer.msg('密码未填!', function () {
                return false;
            });
        } else if (captcha.trim() == '') {
            layer.msg('验证码未填!', function () {
                return false;
            });
        } else {
            login();
        }

        function login() {
            $.ajax({
                url: "getLoginData",
                data: {'account': account, 'password': password, 'captcha': captcha},
                type: "post",
                dataType: "json",
                success: function (data) {
                    if (data.code == 0) {
                        layer.msg(data.msg, function () {
                                $('#captcha').attr('src', 'captcha?' + Math.random())
                            return false;
                        });
                    } else {
                        layer.msg(data.msg, {icon: 1});
                        setTimeout(function () {
                            location.href = "index";
                        }, 1100);
                    }
                }
            });
        }
    });

});