
$('#login_btn').click(function () {
    if($('input[name=account]').val() == ''){
        jeBox.msg('请输入用户名！', {icon: 6,time: 2});
        return false;
    }

    if($('input[name=password]').val() == ''){
        jeBox.msg('请输入密码！', {icon: 6,time: 2});
        return false;
    }

    if($('input[name=code]').val() == ''){
        jeBox.msg('请输入验证码！', {icon: 6,time: 2});
        return false;
    }

    submit2('VerificationCode','/login/login','/','登录成功！');
});