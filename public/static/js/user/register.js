$(function(){  
    $(".registerform2").Validform({
        tiptype:2,
        showAllError:true,
        postonce:true,
        datatype:{
            "z2-5":function isValidBusCode(busCode){
                    if(busCode == ''){
                        return false;
                    }
                    var ret=false;
                        if(busCode.length > 5 && busCode.length < 19){
                            var sum=0;
                            var s=[];
                            var p=[];
                            var a=[];
                            var m=10;
                            p[0]=m;
                            for(var i=0;i<busCode.length;i++){
                                a[i]=parseInt(busCode.substring(i,i+1),m);
                                s[i]=(p[i]%(m+1))+a[i];
                                if(0==s[i]%m){
                                    p[i+1]=10*2;
                                }else{
                                    p[i+1]=(s[i]%m)*2;
                                }
                            }
                            if(1==(s[14]%m)){
                                //营业执照编号正确!
                                //alert('营业执照编号正确!');
                                ret=true;
                            }else{
                                //营业执照编号错误!
                                ret=false;
                                //alert('营业执照编号错误!');
                            }
                        }else if(""==busCode){
                            ret=true;
                        }

                        return ret;
                    },
                "mnew":function is_iphone(iphone) {
                    var myreg = /^1[0-9]{10}$/;//手机号码第二位不验证，确保长期有效
                    //var myreg = /^(((13[0-9]{1})|(15[0-9]{1})|(17[0-9]{1})|(18[0-9]{1}))+\d{8})$/;
                    if(!myreg.test(iphone))
                    {
                        return false;
                    }
                    return true;
                },
                "z2-6":function isValidBusCode(busCode){
                    if(busCode == ''){
                        return false;
                    }
                        var ret= '';
                        if(busCode.length > 5 && busCode.length < 19){
                            for(var i=1;i<busCode.length+1;i++){
                                    if(busCode.length != i){
                                        var p = /^[0-9]*$/; 
                                        var b = p.test(busCode[i-1]);
                                        if(!b){
                                            ret += i+'.';
                                        }
                                    }else{
                                        //最后一位可以是数字和字母
                                        var p = /^[A-Za-z0-9]*$/; 
                                        var b = p.test(busCode[i-1]);
                                        if(!b){
                                            ret += i+'.';
                                        }
                                    }
                                }
                        }else{
                            return false;
                        }
                        if(ret.length > 0){
                            return false;
                        }
                        return true;
                    },
            "z8-20": function is_pwd(pwd) {
                var pw = pwd.replace(/\s*/g,'');
                var reg = /^\d+$/g;
                if(reg.test(pw) || pw.length < 8 || pw.length > 20){
                    return false;
                }
                return true;
            },
        },
        beforeSubmit:function(curform){
                //在验证成功后，表单提交前执行的函数，curform参数是当前表单对象。
                //这里明确return false的话表单将不会提交;
            var aa = document.getElementById('radio-1-2').checked;
                if(!aa){
                    jeBox.msg('请阅读用户注册协议并同意！', {icon: 6});
                    return false;
                }
                // var dataarr = $(curform).serializeArray();
                // var account = dataarr[0].value; //用户名
                // var load_psw = dataarr[2].value;//密码
                // var load_psw2 = dataarr[3].value;//确认密码
                // var mobile = dataarr[7].value;  //手机号码
                // var sms_code = dataarr[8].value;//手机验证码
                // var promo_code = dataarr[4].value;//推荐人信息
                // var business_licence = dataarr[6].value; //营业执照
                // var organizationtype = dataarr[5].value; //组织机构类型
                // var enterprise_name =  dataarr[1].value; //公司名称
                // var code = dataarr[9].value;//图形验证码
                     $.ajax({
                     type: "post",
                     url: "/register/enterpriseRegister_caerte",
                     data: $("#tf2").serialize(),
                     dataType: "json",
                     success: function(data){
                         $("input[name=__token__]").val(data.token);
                            if(data.code == '1'){
                                jeBox.msg('注册成功！', {icon: 7});
                                    setTimeout(function(){
                                        window.location.href="/login/index";
                                    },1000);
                            }else{
                                //刷新验证码
                                $("#yanzhengma2").click();
                                $("input[name=codes]").val('');
                                jeBox.msg(data.msg, {icon: 6});
                                
                            }                 
                          }
                    }); 
                return false;   
                },
            callback:function(data){
                return false;
            }
    });

    var shi=180;
    $('.yan1').click(function(){
        //增加验证手机号码是否被注册，
        if($(".tel").hasClass("Validform_error") || $(".tel").val() == ''){
            return false;
            }
            //判断按钮是否被点击
            if($(".yan1").attr('data') == 1){
                return false;
            }
            if(!validatemobile($(".tel").val())){
                jeBox.msg('手机号码格式不正确！', {icon: 6});
                return false;
            }
            //将手机号码赋值到验证码表单
            //$(".code").attr('name',$(".tel").val());
            //发送验证码
            $.ajax({
             type: "post",
             url: "/register/verify_code",
             data: {mobile:$(".tel").val()},
             dataType: "json",
             beforeSend: function(){
                 $('.yan1').html('发送中..');
                 $(".yan1").attr('data','1')
             },
             success: function(data){

                    if(data.code == '1'){
                        $(".yan1").attr('data','1');
                        //成功 
                        if(shi==180){
                            var time = setInterval(function(){
                                shi-=1;
                                $('.yan1').text('重新发送('+shi+')').css('background','#D6D6D6')

                                if(shi==0){
                                    shi=180;
                                    clearInterval(time)
                                    $('.yan1').text('获取验证码').css('background','#40abf3')
                                    $(".yan1").attr('data','0')
                                    }
                                },1000)
                        }

                        jeBox.msg(data.msg, {icon: 7});
                    }else{
                        $('.yan1').html('获取验证码');
                        $(".yan1").attr('data','0');
                        jeBox.msg(data.msg, {icon: 6});
                    }
                    
                  }
            });     
    })




    //企业用户注册手机验证码
        var shi2=180;
    $('.yan2').click(function(){
        //增加验证手机号码是否被注册，
        if($(".tel2").hasClass("Validform_error") || $(".tel2").val() == ''){
            return false;
            }
            //判断按钮是否被点击
            if($(".yan").attr('data') == 1){
                return false;
            }
            //$(".code2").attr('name',$(".tel").val());
            if(!validatemobile($(".tel2").val())){
                jeBox.msg('手机号码格式不正确！', {icon: 6});
                return false;
            }
            //发送验证码
            $.ajax({
             type: "post",
             url: "/register/verify_code",
             data: {mobile:$(".tel2").val()},
             dataType: "json",
             beforeSend: function(){
                $('.yan2').html('发送中..').css('background','#40abf3')
                $(".yan2").attr('data','1')
             },
             success: function(data){
                    if(data.code == '1'){
                        //成功 
                        if(shi2==180){
                            var time = setInterval(function(){
                                shi2-=1;
                                $('.yan2').text('重新发送('+shi2+')').css('background','#D6D6D6')

                                if(shi2==0){
                                    shi2=180;
                                    clearInterval(time)
                                    $('.yan2').text('获取验证码').css('background','#40abf3')
                                    $(".yan2").attr('data','0');
                                    }
                                },1000)
                        }
                        jeBox.msg(data.msg, {icon: 7});
                    }else{
                        $('.yan2').html('获取验证码').css('background','#40abf3')
                        $(".yan2").attr('data','0')
                        jeBox.msg(data.msg, {icon: 6});
                    }
                  }
            });     
    })

        $(".registerform").Validform({
            tiptype:2,
            showAllError:true,
            postonce:true,
            datatype:{
            "z2-5":function isValidBusCode(busCode){
                    if(busCode == ''){
                        return false;
                    }
                    var ret=false;
                        if(busCode.length > 5 && busCode.length < 19){
                            var sum=0;
                            var s=[];
                            var p=[];
                            var a=[];
                            var m=10;
                            p[0]=m;
                            for(var i=0;i<busCode.length;i++){
                                a[i]=parseInt(busCode.substring(i,i+1),m);
                                s[i]=(p[i]%(m+1))+a[i];
                                if(0==s[i]%m){
                                    p[i+1]=10*2;
                                }else{
                                    p[i+1]=(s[i]%m)*2;
                                }
                            }
                            if(1==(s[14]%m)){
                                //营业执照编号正确!
                                //alert('营业执照编号正确!');
                                ret=true;
                            }else{
                                //营业执照编号错误!
                                ret=false;
                                //alert('营业执照编号错误!');
                            }
                        }else if(""==busCode){
                            ret=true;
                        }

                        return ret;
                    },
                "z2-6":function isValidBusCode(busCode){
                    if(busCode == ''){
                        return false;
                    }
                        var ret= '';
                        if(busCode.length > 5 && busCode.length < 19){
                            for(var i=1;i<busCode.length+1;i++){
                                    if(busCode.length != i){
                                        var p = /^[0-9]*$/; 
                                        var b = p.test(busCode[i-1]);
                                        if(!b){
                                            ret += i+'.';
                                        }
                                    }else{
                                        //最后一位可以是数字和字母
                                        var p = /^[A-Za-z0-9]*$/; 
                                        var b = p.test(busCode[i-1]);
                                        if(!b){
                                            ret += i+'.';
                                        }
                                    }
                                }
                        }else{
                            return false;
                        }
                        if(ret.length > 0){
                            return false;
                        }
                        return true;
                    },
                "mnew":function is_iphone(iphone) {
                    var myreg = /^1[0-9]{10}$/;//手机号码第二位不验证，确保长期有效
                    //var myreg = /^(((13[0-9]{1})|(15[0-9]{1})|(17[0-9]{1})|(18[0-9]{1}))+\d{8})$/;
                    if(!myreg.test(iphone))
                    {
                        return false;
                    }
                    return true;
                },
                "z8-20": function is_pwd(pwd) {
                    var pw = pwd.replace(/\s*/g,'');
                    var reg = /^\d+$/g;
                    if(reg.test(pw) || pw.length < 8  || pw.length > 20){
                        return false;
                    }
                    return true;
                },
        },
            beforeSubmit:function(curform){
                //在验证成功后，表单提交前执行的函数，curform参数是当前表单对象。
                //这里明确return false的话表单将不会提交;
                var aa = document.getElementById('radio-1-1').checked;
                if(!aa){
                    jeBox.msg('请阅读用户注册协议并同意！', {icon: 6});
                    return false;
                }
                // var dataarr = $(curform).serializeArray();
                // var account = dataarr[0].value;
                // var load_psw = dataarr[3].value;
                // var mobile = dataarr[1].value;
                // var sms_code = dataarr[2].value;
                // var promo_code = dataarr[5].value;
                // var code = dataarr[6].value;
                     $.ajax({
                     type: "post",
                     url: "/register/personRegister_caerte",
                     data: $("#tf").serialize(),
                     dataType: "json",
                     success: function(data){
                         $("input[name=__token__]").val(data.token);
                            if(data.code == '1'){
                                jeBox.msg('注册成功！', {icon: 7});
                                    setTimeout(function(){
                                        window.location.href="/login/index";
                                    },1000);
                            }else{
                                //刷新验证码
                                $("#yanzhengma").click();
                                $("input[name=code]").val('');
                                jeBox.msg(data.msg, {icon: 6});
                                
                            }              
                          }
                    }); 
                return false;   
                },
            callback:function(data){
                return false;
            }
        });
})

/**
 * 计算字符长度
 * @param val
 * @returns {number}
 */
function getByteLen(val) {
            var len = 0;
            for (var i = 0; i < val.length; i++) {
                var a = val.charAt(i);
                if (a.match(/[^\x00-\xff]/ig) != null) {
                    len += 2;
                }
                else {
                    len += 1;
                }
            }
            return len;
        }

