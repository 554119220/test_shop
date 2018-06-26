var shi = 180
$('.getiphone').click(function(){
    if($("input[name=account]").val() == ''){
        jeBox.msg('请输入用户名！', {icon: 6});

        return false;
    }
    if($("input[name=mobile]").val() == ''){
        jeBox.msg('请输入手机号码！', {icon: 6});
        return false;
    }
    if(!validatemobile($("input[name=mobile]").val())){
        jeBox.msg('手机号码格式不正确！', {icon: 6});
        return false;
    }
    $('.getiphone').attr('disabled','disabled')
    $.ajax({
        type: "POST",
        url: "/login/sms_code",
        data: {mobile:$("input[name=mobile]").val(),account:$("input[name=account]").val(),__token__:$("input[name=__token__]").val()},
        dataType: "json",
        beforeSend: function(){
            $('.getiphone').text('发送中..');
        },
        success: function(data){
            $("input[name=__token__]").val(data.token);
            if(data.code == 1){
                jeBox.msg('发送成功！', {icon: 7,time:2});
                if(shi==180){
                    var time = setInterval(function(){
                    shi-=1;
                    $('.getiphone').text('重新发送('+shi+')').css('background','#D6D6D6')
                    $('.getiphone').attr('disabled','disabled')
                    if(shi==0){
                        shi=180;
                        clearInterval(time)
                        $('.getiphone').text('获取验证码').css('background','#40abf3')
                        $('.getiphone').removeAttr('disabled')
                        }
                    },1000)
                }
            }else{
                jeBox.msg(data.msg, {icon: 6,time:2});
                $('.getiphone').text('获取验证码');
                $('.getiphone').removeAttr('disabled')
            }
        }
    });

    
})
$('.getemail').click(function(){
    if($(".name1").val() == ''){
        jeBox.msg('请输入用户名！', {icon: 6,time:1000});
        return false;
    }

    if($("input[name=email]").val() == ''){
        jeBox.msg('请输入email！', {icon: 6});
        return false;
    }
    $('.getemail').attr('disabled','disabled')
    // if(!validatemobile($("input[name=email]").val())){
    //     toast({msg:'email格式不正确！'});
    //     return false;
    // }

    $.ajax({
        type: "POST",
        url: "/login/email_code",
        data: {email:$("input[name=email]").val(),account:$(".name1").val(),__token__:$("input[name=__token__]").val()},
        dataType: "json",
        beforeSend: function(){
            $('.getemail').text('发送中..');
        },
        success: function(data){
            $("input[name=__token__]").val(data.token);
            if(data.code == 1){
                jeBox.msg('发送成功！', {icon: 7,time:2});
                if(shi==180){
                    var time = setInterval(function(){
                    shi-=1;
                    $('.getemail').text('重新发送('+shi+')').css('background','#D6D6D6')
                    $('.getemail').attr('disabled','disabled')
                    if(shi==0){
                        shi=180;
                        clearInterval(time)
                        $('.getemail').text('获取验证码').css('background','#40abf3')
                        $('.getemail').removeAttr('disabled')
                        }
                    },1000)
                }
            }else{
                jeBox.msg(data.msg, {icon: 6});
                $('.getemail').removeAttr('disabled')
                $('.getemail').text('获取验证码');
            }
        }
    });
})

$('.set2_mi').click(function(){
    if($('input[name=old_password]').val() == ''){
        jeBox.msg('请输入原密码！', {icon: 6});
        return false;
    }
    if($('input[name=password]').val() != ''){
        var pw = $('input[name=password]').val().replace(/\s*/g,'');
        if(pw.length < 8){
            jeBox.msg('新密码长度至少8位！', {icon: 6});
            return false;
        }
        if(pw.length > 20){
            jeBox.msg('新密码长度不能超过20位！', {icon: 6});
            return false;
        }
        var reg = /^\d+$/g;
        if(reg.test(pw)){
            jeBox.msg('新密码不能全部为数字！', {icon: 6});
            return false;
        }
    }else{
        jeBox.msg('请输入新密码！', {icon: 6});
        return false;
    }

    if($('input[name=password]').val() == $('input[name=old_password]').val()){
        jeBox.msg('原密码和新密码不能相同！', {icon: 6});
        return false;
    }

    if($('input[name=password]').val() != $('input[name=password2]').val()){
        jeBox.msg('新密码和确认新密码不一致！', {icon: 6});
        return false;
    }
    submit2('tf','/userdata/updatepwds','/userdata/securityset','修改成功，下次登录请使用新密码登录！');

});




