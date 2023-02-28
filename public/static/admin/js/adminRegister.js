//验证码
$(function () {
    $('.captcha').click(function () {
        $('#pic').attr('src', 'captcha?' + Math.random())
    })
});
//帐号是否已有判断
$(function () {
    $('.account_input').blur(function () {
        let adminAccount = $('.account_input').val().trim();
        $.ajax({
            url: "IsAdminExit",
            data: {'adminAccount': adminAccount},
            type: "post",
            dataType: "json",
            success: function (data) {
                if (data.code == 0) {
                    $('.isExit').text('帐号已存在!');
                    $('.mb2').hide();

                } else {
                    $('.isExit').text('');
                    $('.mb2').show();
                }
            }
        });
    })
});

//二次密码验证
$(function () {
    $('.ag_password').blur(function () {
        if ($(".ag_password").val() != $(".psw").val()) {
            $('.agw').text('两次密码不一致！');
            $('.submit').hide();
        } else {
            $('.agw').text('');
            $('.submit').show();
        }
    })
});

//通行证验证
$(function () {
    $('#pass-check').blur(() => {
        if ($('#pass-check').val() !== '1161105403') {
            $('.passCheck').text('通行证无效!')
            $('.submit').hide()
        } else {
            $('.passCheck').text('');
            $('.submit').show()
        }
    })
});

//登录表单提交
layui.use('layer', function () {
    var layer = layui.layer;
    $("#sub").on("click", function () {
        let adminName = $(" input[ name='adminName' ] ").val();
        let adminAccount = $(" input[ name='adminAccount' ] ").val();
        let password = $(" input[ name='password' ] ").val();
        let captcha = $(" input[ name='captcha' ] ").val();
        if (adminName.trim() == '') {
            layer.msg('用户名未填!', function () {
                return false;
            });
        } else if (adminAccount.trim() == '') {
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
            register();
        }

        function register() {
            $.ajax({
                url: "getAdminRegisterData",
                data: {'adminName': adminName, 'adminAccount': adminAccount, 'password': password, 'captcha': captcha},
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
                            location.href = "adminbloglist";
                        }, 1100);
                    }
                }
            });
        }
    });

});
