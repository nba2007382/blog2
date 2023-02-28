function praiseStatus(bid) {
    layer.confirm('您想？', {
        btn: ['查看博文', '取消点赞'] //按钮
    }, function () {
        window.location.href = "show?bid=" + bid;
    }, function () {
        layer.confirm('真的要取消吗？', {
            time: 20000, //20s后自动关闭
            btn: ['毫不犹豫', '不忍心唉~']
        }, function () {
            $.ajax({
                type: 'get',
                url: 'praiseStatus',
                data: {bid},
                dataType: 'json',
                success: res => {
                    if (res.code == 1) {
                        window.location.reload();
                    } else {
                        layer.msg(res.msg, function () {
                        });
                    }
                }
            });
        }, function () {
            return true;
        });
    });

}