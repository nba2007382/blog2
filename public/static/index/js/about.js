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
    })
});
//搜索
//搜索
$(function () {
    $('#search').on('click', function () {
        let key = $('#key').val().trim();
        location.href = "searchlist" + "?key=" + key;
    })
});
//导航栏
$(function () {
    $('.nav-list li').hover(function () {
        $(this).children('ul').slideDown(500);
    }, function () {
        $(this).children('ul').slideUp(500);
    })
});
layui.use('element', function () {
    var $ = layui.jquery
        , element = layui.element; //Tab的切换功能，切换事件监听等，需要依赖element模块

    $('.site-demo-active').on('click', function () {
        var othis = $(this), type = $(this).data('type');
        active[type] ? active[type].call(this, othis) : '';
    });
});
