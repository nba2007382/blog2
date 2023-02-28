//********获取省*******
$.ajax({
    type: 'get',
    url: "city",
    data: {type: 'province'},
    dataType: "json",
    success: function (data) {
        var res = data;
        var province = document.getElementById('province');
        for (var i in res) {
            var option = document.createElement('option');
            option.setAttribute('value', res[i].Pcode);
            option.innerText = res[i].Pname;
            province.appendChild(option);
        }
    },
});

// ******切换省，获取市*******
layui.use('form', function () {
    var form = layui.form;
    //监听提交
    form.on('select(sheng)', function (data) {
        var pcode = data.value;
        $.ajax({
            type: 'get',
            url: "city",
            data: {type: 'city', Pcode: pcode},
            dataType: "json",
            success: function (data) {
                console.log(data)
                $('#city').html('<option value="0">请选择一个城市</option>');
                $('#county').html('<option value="0">请选择一个县/区</option>');
                for (var i in data) {
                    var option = $('<option>');
                    option.attr('value', data[i].Ccode);
                    option.text(data[i].Cname);
                    $('#city').append(option);
                }
                form.render();
            }
        });
    });
});
// ******切换市，获取县*******
layui.use('form', function () {
    var form = layui.form;
    //监听提交
    form.on('select(shi)', function (data) {
        var ccode = data.value;
        $.ajax({
            type: 'get',
            url: "city",
            data: {type: 'county', Ccode: ccode},
            dataType: "json",
            success: function (data) {
                console.log(data)
                $('#county').html('<option value="0">请选择一个县/区</option>');
                for (var i in data) {
                    var option = $('<option>');
                    option.attr('value', data[i].Acode);
                    option.text(data[i].Aname);
                    $('#county').append(option);
                }
                form.render();
            }
        });
    });
});

layui.use('form', function () {
    var form = layui.form;
    form.on('submit(go)', function (data) {
        console.log(data.elem) //被执行事件的元素DOM对象，一般为button对象
        console.log(data.form) //被执行提交的form对象，一般在存在form标签时才会返回
        console.log(data.field) //当前容器的全部表单字段，名值对形式：{name: value}
        let res = data.field;
        //获取checkbox[name='like']的值
        var check_arr = new Array();
        $("input:checkbox[name='hobby']:checked").each(function (i) {
            check_arr[i] = $(this).val();
        });
        $.ajax({
            type: 'post',
            url: "getAccount",
            data: {'data': res, 'checkBox': check_arr},
            dataType: "json",
            success: function (data) {
                if (data.code == 0) {
                    layer.msg(data.msg)
                    return false;
                } else {
                    layer.msg(data.msg, {icon: 1});
                    setTimeout(function () {
                        window.location.reload();
                    }, 1000)
                }
            }
        });
        return false;
    });

});
