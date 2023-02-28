//导航栏
$(function () {
    $('.nav-list li').hover(function () {
        $(this).children('ul').slideDown(500);
    }, function () {
        $(this).children('ul').slideUp(500);
    })
});
//搜索
$(function () {
    $('#search').on('click', function () {
        let key = $('#key').val().trim();
        location.href = "searchlist" + "?key=" + key;
    })
});
//侧边栏效果
var btnObjs = document.getElementsByClassName("cls");
for (var i = 0; i < btnObjs.length; i++) {
    btnObjs[i].addEventListener('click', function () {
        for (var j = 0; j < btnObjs.length; j++) {
            btnObjs[j].style.backgroundColor = "";
        }
        this.style.backgroundColor = "#00A1D7";
    })
}
$(function () {
    $(".sidebar-list li").mouseenter(function () {
        $(this).children("span").stop().animate({left: 0});
    }).mouseleave(function () {
        $(this).children("span").stop().animate({left: -167});
    });

});
// $(function ($) {
//     $('.cls').on('click', function () {
//         var url = $(this).attr('href')
//         $('#main').load(url + ' #main > *')
//         return false
//     })
// });
//渲染表单样式
layui.use('form', function () {
    var form = layui.form; //只有执行了这一步，部分表单元素才会自动修饰成功

    //……

    //但是，如果你的HTML是动态生成的，自动渲染就会失效
    //因此你需要在相应的地方，执行下述方法来手动渲染，跟这类似的还有 element.init();
    form.render();
});
//渲染日期
layui.use('laydate', function () {
    var laydate = layui.laydate;
    laydate.render({
        elem: '#date'
    });
});
