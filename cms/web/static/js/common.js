/*
* 选择店铺分类
* */
//function selectShopClassify(obj){
//    //获取一级的序号
//    var nextClassifyId = 'classify_id_'+Number(obj.attr('data')) + 1;
//    //var classifyObj = eval("("+<?=$shop_classify_json?>+")");
//    console.log(classifyObj);
//}

function aaas(data){
    alert(data);
}


 function batch_submit(t){
    if($('input[name="selection[]"]:checked').length < 1){
        return false;
    }
    var nowHtml = $(t).html()
    var param = $(t).attr('param')
    var ajax_url = $(t).attr('ajax_url')
    var params = ''
    $.each($('input[name="selection[]"]:checked'),function(){
        if(params == ''){
            params = $(this).val();
        }else{
            params = params +","+ $(this).val();
        }
    });
    if(param !== '' && typeof(param) !== 'undefined'){
        params = params + '&'+param;
    }
    var msg = '您确定要'+nowHtml+'吗?';
    layer.confirm(msg, {
        btn: ['是','否']
    }, function(){
        $.ajax({
            url: ajax_url+'&id='+params,
            type: 'GET',
            dataType: 'json',
            success: function (msg) {
                layer.msg('操作成功', {icon: 1});
            },
            error:function(){
                layer.msg('操作失败', {icon: 2});
            }
        })
        return true;
    }, function(){
        return true;
    });
}
/*
* 创建子分类
* */
function create_shop_classify(t){
    var classify_pid = Number($(t).attr('classify_pid'));
    if(classify_pid <= 0){
        if($(t).attr('id') != 'create_shop_classify_1'){
            layer.msg('请选择上级分类', {icon: 2});
            return false;
        }
    }
    layer.open({type: 2,title:'添加分类', fix:false,shadeClose:true,maxmin:true,area: ['900px', '600px'],skin: 'layui-layer-rim', content: ['/index.php?r=shop/classify/create&classify_pid='+classify_pid, 'yes'] });

}
function update_shop_classify(id){
    layer.open({type: 2,title:'添加分类', fix:false,shadeClose:true,maxmin:true,area: ['900px', '600px'],skin: 'layui-layer-rim', content: ['/index.php?r=shop/classify/update&id='+id, 'yes'] });
}
/*
 * 修改分类
 * */
/*
* 获取分类的子分类(从数据库获取)
* */
function get_classify_child(id,level){
    nextLevel = Number(level) + 1
    if(nextLevel == 2){
        $('#create_shop_classify_'+nextLevel).attr('classify_pid',id)
    }
    $.ajax({
        url: '/index.php?r=shop/classify/child&classify_pid='+id,
        type: 'GET',
        async:false,
        dataType: 'json',
        success: function(data) {
            var class_td_html = ''
            $.each(data,function(i,item){
                class_td_html += '<tr><td class="checkboxWidth">•</td>';
                if(level > 2){
                    class_td_html += '<td>'+item.classify_name+'</td>';
                }else{
                    class_td_html += '<td onclick="get_classify_child('+item.id+','+nextLevel+')">'+item.classify_name+'</td>';
                }
                class_td_html += '<td class="rightUpdateWidth"><a href="javascript:" onclick="update_shop_classify('+item.id+')">修改</a></td></tr>';
            })
            if(nextLevel == 2){
                $('#classify_level_3').html('')
            }
            $('#classify_level_'+nextLevel).html(class_td_html)
        },
        error:function(){
            layer.msg('获取失败', {icon: 2});
        }
    });

}
function update_classify_html(id,key,menu_id){
    var class_td_html = ''
    if(id == 2){
        $('#classify_level_3').html('')
    }
    $.each(jsonFileObj[key],function(i,item){
        class_td_html += '<tr><td class="checkboxWidth"><input type="checkbox" value="'+i+'" id="classify_'+i+'" onclick="ajax_submit_binding_classify(this.value,'+menu_id+')"></td>';
        if(id > 2){
            class_td_html += '<td>'+item+'</td>';
        }else{
            class_td_html += '<td onclick="update_classify_html('+(Number(id)+1)+','+i+','+menu_id+')">'+item+'</td>';
        }
        class_td_html += '<td class="rightImgWidth">+</td></tr>';
    })
    $('#classify_level_'+id).html(class_td_html)
    select_binding_classify(id,key,menu_id)
}
/*
* 删除分类
* */
function ajax_delete(t){
    var ajax_url = $(t).attr('ajax_url')
    var nowHtml = $(t).html()
    var msg = '您确定要'+nowHtml+'吗?';
    layer.confirm(msg, {
        btn: ['是','否']
    }, function(){
        $.ajax({
            url: ajax_url,
            type: 'GET',
            dataType: 'json',
            success: function (msg) {
                $(t).parent().parent().remove()
                layer.msg('操作成功', {icon: 1});
            },
            error:function(){
                layer.msg('操作失败', {icon: 2});
            },
            beforeSend:function(msg){
                layer.msg('执行中', {
                    icon: 16
                    ,shade: 0.01
                });
            }
        })
        return true;
    }, function(){
        return true;
    });
}
/*
* 上传图片后修改对应的字段
* */
function ajax_update(ajax_url){
    $.ajax({
        url: ajax_url,
        type: 'GET',
        async:false,
        dataType: 'json',
        error: function(msg) {
            layer.msg('修改失败', {icon: 2});
        },
        success: function(data) {
            layer.msg('修改成功', {icon: 1});

        },
        beforeSend:function(msg){
            layer.msg('执行中', {
                icon: 16
                ,shade: 0.01
            });
        }
    });
}
 /*
 * 选择分类
 * */
function ajax_select(t){
    var selected_css = $(t).attr('selected_css')
    var ajax_url = $(t).attr('ajax_url')
    $.ajax({
        url: ajax_url,
        type: 'GET',
        async:false,
        dataType: 'json',
        error: function(msg) {
            layer.msg('操作失败', {icon: 2});
        },
        success: function(data) {
            if(data.status == 201){
                window.location.href=data.refrom
            }else{
                $(t).attr('css',selected_css)
                layer.msg('操作成功', {icon: 1});
            }
        },
        beforeSend:function(msg){
            layer.msg('执行中', {
                icon: 16
                ,shade: 0.01
            });
        }
    });
}
/*
* 生成店铺分类的静态文件
* */
function ajax_get(t){
    var ajax_url = $(t).attr('ajax_url')
    $.ajax({
        url: ajax_url,
        type: 'GET',
        async:false,
        dataType: 'json',
        error: function(msg) {
            layer.msg('操作失败', {icon: 2});
        },
        success: function(msg) {
            layer.msg('操作成功', {icon: 1});
        },
        beforeSend:function(msg){
            layer.msg('执行中', {
                icon: 16
                ,shade: 0.01
            });
        }
    });
}
 /*
* 选中已绑定的分类
* */
function select_binding_classify(classify_id,key,menu_id){
    $.ajax({
        url: 'index.php?r=channel/menu/binding&type=get&classify_id='+classify_id+'&menu_id='+menu_id,
        type: 'GET',
        dataType: 'json',
        success: function (jsonFile) {
            $.each(jsonFile[key],function(i,item){
                $('#classify_'+item).attr("checked", true);
            });
        }
    });
}
function ajax_submit_binding_classify(classify_id,menu_id){
    $.ajax({
        url: 'index.php?r=channel/menu/binding&classify_id='+classify_id+'&menu_id='+menu_id,
        type: 'GET',
        dataType: 'json',
        async:false,
        success: function (msg) {
            layer.msg('操作成功', {icon: 1});
        },
        beforeSend:function(msg){
            layer.msg('执行中', {
                icon: 16
                ,shade: 0.01
            });
        }
    });
}
/*
* 跳转前提示
* */
function historyGo(t){
    var url = $(t).attr('url');
    var html = $(t).html();
    var msg = '您确定要'+html+'吗?';
    layer.confirm(msg, {
        btn: ['是','否']
    }, function(){
        window.location = url;
    });
}
function ajax_submit(t){
    var status = $(t).attr('status');
    var endStatus = $(t).attr('endStatus');
    if(endStatus !== '' && typeof(endStatus) !== 'undefined'){
        endStatus = 2
    }
    var nowHtml = $(t).html()
    var beforeHtml = $(t).attr('beforeHtml')
    var ajax_url = $(t).attr('ajax_url')
    var id = $(t).attr('id');
    var msg = '您确定要'+nowHtml+'吗?';
    layer.confirm(msg, {
        btn: ['是','否']
    }, function(){
        $.ajax({
            url: ajax_url+'&status='+status+"&endStatus"+endStatus,
            type: 'GET',
            dataType: 'json',
            success: function (msg) {
                layer.msg('操作成功', {icon: 1});
                $(t).attr('status',endStatus);
                $(t).attr('endStatus',status);
                $(t).attr('beforeHtml',nowHtml)
                $(t).html(beforeHtml);
                $('#status'+id).html(nowHtml);
            },
            error:function(){
                layer.msg('操作失败', {icon: 2});
            }
        })
        return true;
    }, function(){
        return true;
    });
}

function daysBetween(sDate1,sDate2){
//Date.parse() 解析一个日期时间字符串，并返回1970/1/1 午夜距离该日期时间的毫秒数
    var time1 = Date.parse(new Date(sDate1));
    var time2 = Date.parse(new Date(sDate2));
    var nDays = Math.abs(parseInt((time2 - time1)/1000/3600/24));
    return  nDays;
}

//审核驳回
function examineReject(t){
    var examine_url = $(t).attr('examine_url');
    layer.open({type: 2,title:'审核驳回', fix:false,shadeClose:true,maxmin:true,area: ['900px', '600px'],skin: 'layui-layer-rim', content: [examine_url, 'yes'] });
}
//审核通过
function examineLayer(t){
    var examine_url = $(t).attr('examine_url');
    // console.log(examine_url);
    // return false;
    var msg = '您确定要审核通过吗?';
    layer.confirm(msg, {
        btn: ['是','否']
    }, function(){
        $.ajax({
            url: examine_url,
            type: 'GET',
            dataType: 'json',
            success: function (msg) {
                layer.msg('操作成功', {icon: 1});
            },
            error:function(){
                layer.msg('操作失败', {icon: 2});
            },
            beforeSend:function(msg){
                layer.msg('执行中', {
                    icon: 16
                    ,shade: 0.01
                });
            }
        })
        return true;
    }, function(){
        return true;
    });
}
//send ajax
function sendAjax(parameters) {
    var $data = parameters._data;
    var $url = parameters._url;
    var $success = parameters._success;
    var $error = parameters._error;
    $.ajax({
        url: $url,
        type: 'POST',
        data: $data,
        success:function (phpdata) {
            if(!phpdata){
                layer.msg($error);
                return false;
            }
            if($success){
                if(phpdata){
                    layer.msg($success);
                    parent.location.reload();
                    return false;
                }
            }
        },error:function () {
            layer.msg($error);
            return false;
        }
    })
}
//click cancel button,close the page
$("#canncel").bind('click',function () {
    var pg = parent.layer.getFrameIndex(window.name);
    parent.layer.close(pg);
})



//导出列表
// $('.export').click(function () {
//     var leds = $('.talbe tbody tr').length;
//     if(parseInt(leds) < 1){
//         layer.msg('当前条件下没有数据导出');
//         return false;
//     }
// })


