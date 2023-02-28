//表格渲染
layui.use('table', function () {
    var table = layui.table;
    table.render({
        elem: '.mycomments-list'
        , url: "myCommentListQuery"
        , toolbar: '#toolbarDemo' //开启头部工具栏，并为其绑定左侧模板
        , page: { //支持传入 laypage 组件的所有参数（某些参数除外，如：jump/elem） - 详见文档
            layout: ['limit', 'count', 'prev', 'page', 'next', 'first', 'skip'] //自定义分页布局
            //,curr: 5 //设定初始在第 5 页
            , limit: 10 //一页显示多少条
            , limits: [5, 10, 14]//每页条数的选择项
            , groups: 5 //只显示 2 个连续页码
            , first: "首页" //不显示首页
            , last: "尾页" //不显示尾页
        }
        , cols: [[
            {type: 'numbers', width: 80, title: 'ID', sort: true}
            , {field: 'title', width: 110, title: '标题'}
            , {field: 'intro', width: 150, title: '博文内容'}
            , {field: 'evaluate', width: 120, title: '星级', sort: true}
            , {field: 'content', width: 170, title: '留言内容'}
            , {field: 'cdate', width: 120, title: '评论时间', sort: true}
            , {fixed: 'right', title: '操作', toolbar: '#operate', width: 120}
        ]],
        id: 'commentsTableReload'
    });
    //表格的重载starting
    //根据条件查询表格数据重新加载
    var $ = layui.$, active = {
        reload: function () {
            //获取输入值
            var demoReload = $('#content');
            //执行重载
            table.reload('commentsTableReload', {
                where: {
                    content: demoReload.val()
                }
            });
        }
    };
    //点击搜索按钮根据用户名称查询
    $('#selectbyCondition').on('click',
        function () {
            if ($('#content').val().trim() == '') {
                layer.msg('输入内容不能为空！！！', function () {
//关闭后的操作
                });
                return false
            }
            var type = $(this).data('type');
            active[type] ? active[type].call(this) : '';
        }
    );
    //    表格的重载ending
});

//博文查看
function confirmSee(bid) {
    layer.alert('查看该博文？', {icon: 6}, function () {
        window.location.href = "show?bid=" + bid;
    });
}

//留言删除
function confirmDel(cid) {
    layer.confirm('你确定要删除吗？', {
        btn: ['下次再见吧~', '于心不忍呀~'] //按钮
    }, function () {
        $.ajax({
            type: 'get',
            url: "myCommentDel",
            data: {cid},
            dataType: 'json',
            success: function (e) {
                if (e.code == 1) {
                    layer.msg(e.msg, {icon: 1});
                    setTimeout(function () {
                        location.reload();
                    }, 1000);
                } else {
                    layer.msg(e.msg, function () {
//关闭后的操作
                        setTimeout(function () {
                            location.reload();
                        }, 800)
                    });
                }
            }
        });
    });
}