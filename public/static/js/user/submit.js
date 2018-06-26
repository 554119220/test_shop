    /**
     * 跳转提交支付
     * @param form 表单id
     * @param url 表单提交地址
     * @param urls 表单提交成功后的跳转地址
     */
    function submit(form,url,urls){
        $.ajax({
            url:url,
            type:"post",
            data:$("#"+form).serialize(),
            dataType: "json",
            success:function(data){
                $("input[name=__token__]").val(data.token);
                if(data.code == 1){
                    //成功
                    window.location.href = urls+data.data.code;
                }else if(data.code == 1000){
                    //从新登陆
                    jeBox.msg('您已退出，重新登录！', {icon: 6});
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

    /**
     * 表单提交
     * @param form 表单id (VerificationCode时刷新验证码,pay时跳转支付页面)
     * @param url 表单提交地址
     * @param urls 表单提交成功后的跳转地址 默认或者空时刷新当前页面
     * @param msgs 表单提交成功后的提示信息 默认是“成功！”
     * @param Handle 表单点击之后不允许再次点击提交，如果返回错误信息则可以再次点击
     */
    function submit2(form,url,urls,msgs,Handle){
        $.ajax({
            url:url,
            type:"post",
            data:$("#"+form).serialize(),
            dataType: "json",
            beforeSend: function(){
                if(Handle){
                    $("."+Handle).attr("disabled", "disabled");
                }
                //$(".SubmitData").attr("disabled", "disabled");
            },
            success:function(data){
                $("input[name=__token__]").val(data.token);
                if(data.code == 1){
                    //成功
                    var messages = '成功！';
                    if(msgs){
                        messages = msgs;
                    }
                    jeBox.msg(messages, {icon: 7});
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
                    jeBox.msg('您已退出，重新登录！', {icon: 6});
                    window.location.reload();
                }else{
                    if(Handle) {
                        $("." + Handle).removeAttr("disabled");
                    }
                    $('input[name=__token__]').val(data.token);
                    jeBox.msg(data.msg, {icon: 6});
                    if(form == 'VerificationCode'){
                        $("#yanzhengma").click();
                        $('.clare').val('');
                    }

                }

                // setTimeout(function(){
                //     $(".set2_mi").val("提交");
                //     $(".set2_mi").removeAttr("disabled");
                // },1000);
            },
            error:function(e){
                jeBox.msg('错误！！', {icon: 6});
                if(Handle) {
                    $("." + Handle).removeAttr("disabled");
                }
                    // $(".set2_mi").val("提交");
                    // $(".set2_mi").removeAttr("disabled");
            }
        });
    }


    