//固定导航栏
$(function () {
    $(window).scroll(function () {
        if ($(window).scrollTop() >= $(".header").height()) {
            $(".nav").addClass("fixed");
            $(".nav-bottom").css('marginTop', $(".nav").height() + 10)
        } else {
            $(".nav").removeClass("fixed");
            $(".nav-bottom").css('marginTop', 0);
        }
    });
});
//轮播1
layui.use('carousel', function () {
    var carousel = layui.carousel;
    //建造实例
    carousel.render({
        elem: '.carousel'
        , width: '75%'
        , arrow: 'hover' //始终显示箭头
        , anim: 'fade' //切换动画方式

    });
});
//轮播2
layui.use('carousel', function () {
    var carousel = layui.carousel;
    //建造实例
    carousel.render({
        elem: '.slide img'
        , width: '100%'
        , arrow: 'hover' //始终显示箭头
        , anim: 'fade' //切换动画方式

    });
});
//导航栏
$(function () {
    $('.nav-list li').hover(function () {
        $(this).children('ul').slideDown(500);
    }, function () {
        $(this).children('ul').slideUp(500);
    })
});

//获取地址栏参数
function getURL(variable) {
    var query = window.location.search.substring(1);
    var vars = query.split("&");
    for (var i = 0; i < vars.length; i++) {
        var params = vars[i].split("=");
        if (params[0] == variable) {
            return decodeURI(params[1]);
        }
    }
    return (false);
}

//搜索
$(function () {
    $('#search').on('click', function () {
        let key = $('#key').val().trim();
        location.href = "searchlist" + "?key=" + key;
    })
});

//分享链接添加
$(function () {
    let href = location.href;
    $('.bshareDiv').attr('href', href);
});

//评分
$(function () {
    //1. 给li注册鼠标经过事件，让自己和前面所有的兄弟都变成实心
    var wjx_k = "☆";
    var wjx_s = "★";
    $(".comment-list>li").on("mouseenter", function () {
        $(this).text(wjx_s).prevAll().text(wjx_s);
        $(this).nextAll().text(wjx_k);
    });
    //2. 给ul注册鼠标离开时间，让所有的li都变成空心
    $(".comment-list").on("mouseleave", function () {
        $(this).children().text(wjx_k);
        //再做一件事件，找到current，让current和current前面的变成实心就行。
        $("li.current").text(wjx_s).prevAll().text(wjx_s);

    });
    //3. 给li注册点击事件
    $(".comment-list>li").on("click", function () {
        $(this).addClass("current").siblings().removeClass("current");
        $("li.current").siblings().val(" ");
        var index = $(".current").index() + 1;
        $("li.current").val(index);
        $(".eval").val((index));
    });

});

//点赞
layui.use('layer', function () {
    var $ = layui.jquery, layer = layui.layer;
    $('.praise').click(function () {
        var uid = $('.uid').val();
        let bid = $('.bid').val();
        if (uid == 0) {
            layer.confirm('您还未登录，登录后即可点赞哟~', {
                btn: ['登录', '继续游览'] //按钮
            }, function () {
                location.href = "login";
            })
        } else {
            $.ajax({
                type: 'post',
                url: 'updatePraise',
                data: {bid, uid},
                dataType: 'json',
                success: function (res) {
                    console.log(res)
                    if (res.code == 1) {
                        $('.praise').attr('src', 'static/index/images/common/已点赞.png');
                        $('.praise').next().text(res.sum);
                    } else {
                        $('.praise').attr('src', 'static/index/images/common/赞.png');
                        $('.praise').next().text(res.sum);
                    }
                }
            })
        }
    })
});
//收藏
layui.use('layer', function () {
    var $ = layui.jquery, layer = layui.layer;
    $('.like').click(function () {
        let uid = $('.uid').val();
        let bid = $('.bid').val();
        if (uid == 0) {
            layer.confirm('您还未登录，登录后即可点赞哟~', {
                btn: ['登录', '继续游览'] //按钮
            }, function () {
                location.href = "login";
            })
        } else {
            $.ajax({
                type: 'post',
                url: 'updateLike',
                data: {bid, uid},
                dataType: 'json',
                success: function (res) {
                    console.log(res)
                    if (res.code == 1) {
                        $('.like').attr('src', 'static/index/images/common/已收藏.png');
                        $('.like').next().text(res.sum);
                    } else {
                        $('.like').attr('src', 'static/index/images/common/未收藏.png');
                        $('.like').next().text(res.sum);
                    }
                }
            })
        }
    })
});

//评价留言
layui.use('layer', function () {
    var $ = layui.jquery, layer = layui.layer;
    $('.submit').click(function () {
        let evaluate = $('.eval').val();
        let content = $('.message textarea').val();
        let uid = $('.uid').val();
        let bid = $('.bid').val();
        if (evaluate == '') {
            layer.msg('请进行星级评级后留言~', function () {
                return false;
            });
        } else if (content == '') {
            layer.msg('您还未输留言内容~', function () {
                return false;
            });
        } else {
            $.ajax({
                type: 'post',
                url: 'assessAndComment',
                data: {evaluate, content, uid, bid},
                dataType: 'json',
                success: function (res) {
                    if (res.code == 1) {
                        layer.msg(res.msg);
                        setTimeout(function () {
                            window.location.reload();
                        }, 1000);

                    } else {
                        layer.msg('留言失败...', {icon: 5});
                    }
                }
            })
        }

    });
});