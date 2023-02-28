//侧边栏QQ、微信、微博、邮箱弹窗
function qq() {
    console.log(22)
    layer.open({
        content: '我的QQ：1161105403',
        scrollbar: false
    });
}

function webchat() {
    layer.open({
        content: '我的微信：a1161105403',
        scrollbar: false
    });
}

function weibo() {
    layer.open({
        content: '我的微博：暂无',
        scrollbar: false
    });
}

function email() {
    layer.open({
        content: '我的邮箱：1161105403@qq.com',
        scrollbar: false
    });
}