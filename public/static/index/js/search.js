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
layui.use('flow', function () {
    var key = getURL('key');
    var $ = layui.jquery; //不用额外加载jQuery，flow模块本身是有依赖jQuery的，直接用即可。
    var flow = layui.flow;
    var i = 0;
    flow.load({
        elem: '.article' //指定列表容器
        , isLazyimg: true  //图片懒加载
        , scrollElem: '.article'
        , done: function (page, next) { //到达临界点（默认滚动触发），触发下一页
            var lis = [];
            //以jQuery的Ajax请求为例，请求下一页数据（注意：page是从2开始返回）
            $.get('search' + "?page=" + page + "&key=" + key, function (res) {
                //假设你的列表返回在data集合中
                var praise_bids = [];
                for (let i in res.praise_bid) {
                    for (let j in res.praise_bid[i]) {
                        praise_bids.push(res.praise_bid[i][j]);
                    }
                }
                var like_bids = [];
                for (let i in res.like_bid) {
                    for (let j in res.like_bid[i]) {
                        like_bids.push(res.like_bid[i][j]);
                    }
                }
                layui.each(res.data, function (index, item) {
                    let praiseImg = praise_bids.includes(item.bid) ? "static/index/images/common/已点赞.png" : "static/index/images/common/赞.png";
                    let likeImg = like_bids.includes(item.bid) ? "static/index/images/common/已收藏.png" : "static/index/images/common/未收藏.png";
                    lis.push('<div class="excerpt">\
                <div class="title">\
                 <i class="num">' + (++i) + '</i>\
                <span class="title-info"><a href="show?bid=' + item.bid + '">' + item.title + '</a></span>\
                </div>\
            <div class="detail">\
                    <div class="pic">\
            <img lay-src="' + item.pic + '"></div>\
            <div class="content">\
                    <div class="intro">\
                    <div class="intro-detail">\
            <p>' + item.intro + '</p></div></div>\
            <div class="note"><ul>\
            <li><img src="static/index/images/common/沐歌.png" alt="">' + item.uploadadmin + '</li>\
                    <li><img src="static/index/images/common/眼睛.png" alt="">' + item.visited + '</li>\
                    <li><img src="static/index/images/common/时钟.png" alt="">' + item.uploaddate + '</li>\
                    <li><img src="' + praiseImg + '" alt="点赞" onclick="tools.praise(' + item.bid + ',' + item.praise + ')" class="praise' + item.bid + '"><span>' + item.praise + '</span></li>\
                    <li><img src="' + likeImg + '" alt="收藏" onclick="tools.like(' + item.bid + ',' + item.like + ')" class="like' + item.bid + '"><span>' + item.like + '</span></li>\
            </ul>\
       </div>\
                </div>\
                </div>\
                </div>\
                        </div>');
                });
                //执行下一页渲染，第二参数为：满足“加载更多”的条件，即后面仍有分页
                //pages为Ajax返回的总页数，只有当前页小于总页数的情况下，才会继续出现加载更多
                next(lis.join(''), page < res.pages);
            });
        }
    })
    ;
});
//搜索
$(function () {
    $('#search').on('click', function () {
        let key = $('#key').val().trim();
        location.href = "searchlist" + "?key=" + key;
    })
});

//点赞/收藏
layui.use('layer', function () {
    var $ = layui.jquery, layer = layui.layer;
    var uid = $('.uid').val();
    var _tools = {
        //点赞功能
        praise: function (bid, praise) {
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
                            $('.praise' + bid).attr('src', 'static/index/images/common/已点赞.png');
                            $('.praise' + bid).next().text(res.sum);
                        } else {
                            $('.praise' + bid).attr('src', 'static/index/images/common/赞.png');
                            $('.praise' + bid).next().text(res.sum);
                        }
                    }
                })
            }
        },
        //s收藏功能
        like: function (bid, like) {
            if (uid == 0) {
                layer.confirm('您还未登录，登录后即可收藏哟~', {
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
                            $('.like' + bid).attr('src', 'static/index/images/common/已收藏.png');
                            $('.like' + bid).next().text(res.sum);
                        } else {
                            $('.like' + bid).attr('src', 'static/index/images/common/未收藏.png');
                            $('.like' + bid).next().text(res.sum);
                        }
                    }
                })
            }
        }
    };

    window.tools = _tools;
});