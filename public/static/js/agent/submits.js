    /*
    * 跳转提交支付
    * form 表单id
    * url 表单提交地址
    * urls 表单提交成功后的跳转地址
    */
    function submit(form,url,urls){
        var forms = new FormData(document.getElementById(form));
        $.ajax({
            url:url,
            type:"post",
            data:forms,
            processData:false,
            contentType:false,
            dataType: "json",
            success:function(data){
                if(data.code == 1){
                    //成功
                    //alert('/upgrade_pay/code/'+data.data.code);
                    window.location.href = urls+data.data.code;
                    //Lobibox.notify('success',{msg:'保存成功！',position: "bottom middle",delay: 2000,width: 400});
                }else if(data.code == 1000){
                    //从新登陆
                    window.location.reload();
                }else{
                    jeBox.msg(data.msg, {icon: 6});
                }
            },
            error:function(e){
                jeBox.msg('错误！！', {icon: 6});
            }
        });
    }
    /*
    * 表单提交
    * form 表单id (VerificationCode时刷新验证码,pay时跳转支付页面)
    * url 表单提交地址
    * urls 表单提交成功后的跳转地址 默认或者空时刷新当前页面
    */
    function submit2(form,url,urls){
        var forms = new FormData(document.getElementById(form));
        $.ajax({
            url:url,
            type:"post",
            data:forms,
            processData:false,
            contentType:false,
            dataType: "json",
            success:function(data){
                if(data.code == 1){
                    //成功
                    //alert('/upgrade_pay/code/'+data.data.code);
                    //window.location.href = urls+data.data.code;
                    jeBox.msg(data.msg, {icon: 7});
                    //一秒后刷新当前页面,如果跳转链接为空，刷新当前页面
                    if(urls == ''){
                        setTimeout(function(){
                             window.location.reload();
                         },1000);
                    }else{
                        if(form == 'pay'){
                            urls = urls+data.data.code;
                        }
                        setTimeout(function(){
                            window.location.href = urls;
                        },1000);
                    }
                }else if(data.code == 1000){
                    //从新登陆
                    window.location.reload();
                }else{
                    jeBox.msg(data.msg, {icon: 6});
                    if(form == 'VerificationCode'){
                        $("#yanzhengma").click();
                    }

                }
            },
            error:function(e){
                jeBox.msg('错误！！', {icon: 6});
            }
        });
    }


    