//webuploader 初始化
var uploader = WebUploader.create({

    // swf文件路径
    swf: '__STATIC__webuploader-0.1.5/Uploader.swf',

    // 文件接收服务端。
    server: "qtUpload",

    // 选择文件的按钮。可选。
    // 内部根据当前运行是创建，可能是input元素，也可能是flash.
    pick: '#picker',

    // 不压缩image, 默认如果是jpeg，文件上传前会压缩一把再上传！
    resize: false,
    // 设置文件上传域的name
    fileVal: 'pic',
    // 选完文件后，是否自动上传。
    auto: true,
});
// 回调事件
uploader.on('uploadSuccess', function ($file, response) {
    console.log(response);
    if (response.code == 1) {
        $('.new-icon').find('img').eq(2).hide();
        $('.new-icon label').hide();
        $('#avatar').attr('src', response.msg).fadeIn(1000);
        $('#submit').addClass('submit');
        $('#del').fadeIn(200);
    }
});
// 点击删除事件
$('#del').click(function () {
    let img = $('#avatar').attr('src');
    // 发送ajax
    $.ajax({
        url: 'qtPicDel',
        type: 'delete',
        data: {img},
        success: ret => {
            if (ret.code == 1) {
                layer.msg('删除成功', function () {
                });
                $('#avatar').hide();
                $('#del').hide();
                $('.new-icon').find('img').eq(2).show();
                $('.new-icon label').show();
                $('.submit').removeClass('submit');
            }
        }
    });
});
$(function () {
    $('#submit').on('click', function () {
        let img = $('#avatar').attr('src');
        let uid = $('.uid').val();
        if (!img == '') {
            $.ajax({
                type: 'post',
                url: 'updateIcon',
                data: {img, uid},
                dataType: 'json',
                success: res => {
                    console.log(res)
                    if (res.code == 1) {
                        layer.msg(res.msg, {icon: 1});
                        setTimeout(function () {
                            $('#avatar').attr('src', '');
                            $('#avatar').hide();
                            $('#del').hide();
                            $('.new-icon').find('img').eq(2).show();
                            $('.new-icon label').show();
                            $('.submit').removeClass('submit');
                            $('.pre-img').attr('src', res.img);
                        }, 800);
                    } else {
                        layer.msg(res.msg, function () {
                            return false;
                        });
                    }
                }
            })
        }
    })
});

