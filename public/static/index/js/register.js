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
});
//验证码
$(function () {
    $('#captcha').click(function () {
        $('#captcha').attr('src', 'captcha?' + Math.random())
    })
});
//帐号是否已有判断
$(function () {
    $('.account input').blur(function () {
        let account = $('.account input').val().trim();
        $.ajax({
            url: "getAccountData",
            data: {'account': account},
            type: "post",
            dataType: "json",
            success: function (data) {
                if (data.code == 0) {
                    $('.account span').text('帐号已存在');
                    $('.submit').hide();

                } else {
                    $('.account span').text('');
                    $('.submit').show();
                }
            }
        });
    })
});

//二次密码验证
$(function () {
    $('.again-password input').blur(function () {
        if ($(".password input").val() != $(".again-password input").val()) {
            $('.again-password span').text('两次密码不符');
            $('.submit').hide();
        } else {
            $('.again-password span').text('');
            $('.submit').show();
        }
    })
});


//登录表单提交
layui.use('layer', function () {
    var layer = layui.layer;
    $('.submit').on('click', function () {
        a = $('.again-password span').val();
        console.log(a);
        let uname = $(" input[ name='uname' ] ").val();
        let account = $(" input[ name='account' ] ").val();
        let password = $(" input[ name='password' ] ").val();
        let captcha = $(" input[ name='captcha' ] ").val();
        let email = $(" input[ name='email' ] ").val();
        if (uname.trim() == '') {
            layer.msg('用户名未填!', function () {
                return false;
            });
        } else if (account.trim() == '') {
            layer.msg('帐号未填!', function () {
                return false;
            });
        } else if (password.trim() == '') {
            layer.msg('密码未填!', function () {
                return false;
            });
        } else if (email == '') {
            layer.msg('邮箱未填!', function () {
                return false;
            });
        } else if (captcha.trim() == '') {
            layer.msg('验证码未填!', function () {
                return false;
            });
        } else if ($('.again-password span').val().length > 2) {
            layer.msg('两次密码不一致!', function () {
                return false;
            });
        } else {
            register();
        }

        function register() {
            $.ajax({
                url: "getRegisterData",
                data: {'uname': uname, 'account': account, 'password': password, 'email': email, 'captcha': captcha},
                type: "post",
                dataType: "json",
                success: function (data) {
                    if (data.code == 0) {
                        layer.msg(data.msg, function () {
                            $(function () {
                                $('#pic').attr('src', 'captcha?' + Math.random())
                            });
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
