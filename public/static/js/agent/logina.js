$(".registerform2").Validform({
    tiptype:2,
    showAllError:true,
    postonce:true,
    beforeCheck:function(curform){
        //$(".yzm").find('img').attr('src','/captcha.html'+'?' + Math.random());
    },
    beforeSubmit:function(curform){
                //在验证成功后，表单提交前执行的函数，curform参数是当前表单对象。
                //这里明确return false的话表单将不会提交;
                var dataarr = $(curform).serializeArray();
                var account = dataarr[0].value; //用户名
                var load_psw = dataarr[1].value;//密码
                var code = dataarr[2].value;//密码
                     $.ajax({
                     type: "post",
                     url: "/login/login",
                     data: {account:account,load_psw:load_psw,code:code},
                     dataType: "json",
                     success: function(data){
                            if(data.code == '1'){
                                    if(!!window.ActiveXObject || "ActiveXObject" in window){

                                    }else{
                                        toast({code:1,msg:'登陆成功！'});
                                    }
                                    setTimeout(function(){
                                        window.location.href="/";
                                    },1000);

                            }else{
                                if(!!window.ActiveXObject || "ActiveXObject" in window){
                                    alert(data.msg);
                                }else{
                                    toast({msg:data.msg});
                                }

                                //刷新验证码
                                $("#yanzhengma").click();
                                $("input[name=code]").val('');
                                $(".Validform_checkti").html('');
                            }

                          }
                    });
                return false;
                },
});