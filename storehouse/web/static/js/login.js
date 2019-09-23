layui.use(['form','layer','jquery'],function(){
    var form = layui.form,
        layer = parent.layer === undefined ? layui.layer : top.layer
        $ = layui.jquery;

    //登录按钮
    form.on("submit(login)",function(data){
        var submitButton = $(this);
        submitButton.text("登录中...").attr("disabled","disabled").addClass("layui-disabled");
        var postData = {username:$('#username').val(),password:$('#password').val(),_csrf:$('#_csrf').val()};
        $.ajax({
            url:'/index.php?r=site/login',
            type:'post',
            data:postData,
            dateType:'json',
            success:function(resultPost) {
                resultPost = JSON.parse(resultPost)
                if (resultPost.status == 200) {
                    window.location.href = "/";
                } else {
                    submitButton.text("登录").attr("disabled", false).removeClass("layui-disabled");
                    layer.msg(resultPost.message, {icon: 5});
                }
            },
            error:function(resultPost){
                layer.msg('服务器错误', {icon: 5});
            }
        });
        return false;
    })

    //表单输入效果
    $(".loginBody .input-item").click(function(e){
        e.stopPropagation();
        $(this).addClass("layui-input-focus").find(".layui-input").focus();
    })
    $(".loginBody .layui-form-item .layui-input").focus(function(){
        $(this).parent().addClass("layui-input-focus");
    })
    $(".loginBody .layui-form-item .layui-input").blur(function(){
        $(this).parent().removeClass("layui-input-focus");
        if($(this).val() != ''){
            $(this).parent().addClass("layui-input-active");
        }else{
            $(this).parent().removeClass("layui-input-active");
        }
    })
})
