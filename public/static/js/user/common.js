/**
 * 邮箱验证
 * @param email
 * @returns {boolean}
 */
function checkemail(email){
	 //对电子邮件的验证
	 var myreg = new RegExp("^[a-z0-9]+([._\\-]*[a-z0-9])*@([a-z0-9]+[-a-z0-9]*[a-z0-9]+.){1,63}[a-z0-9]+$");
	 if(!myreg.test(email))
	 {
	     return false;
	 }
	 return true;
}

/**
 * 字符检测函数
 * @param chr
 * @returns {number}
 */
function char_test(chr)
{
    var i;
    var smallch="abcdefghijklmnopqrstuvwxyz";
    var bigch="ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    for(i=0;i<26;i++)
        if(chr==smallch.charAt(i) || chr==bigch.charAt(i))
            return(1);
    return(0);
}

/**
 * 数字和特殊字符检测函数
 * @param chr
 * @returns {number}
 */
function spchar_test(chr)
{
    var i;
    var spch="_-.0123456789";
    for (i=0;i<13;i++)
        if(chr==spch.charAt(i))
            return(1);
    return(0);
}

/**
 * 检测email格式
 * @param str
 * @returns {number}
 */
function email_test(str)
{
    var i,flag=0;
    var at_symbol=0;
//“@”检测的位置
    var dot_symbol=0;
//“.”检测的位置
    for (i=1;i<str.length;i++)
        if(str.charAt(i)=='@')
        {
            at_symbol=i;
            break;
        }
//检测“@”的位置

    if(at_symbol==str.length-1 || at_symbol==0)
        return(2);
//没有邮件服务器域名

    if(at_symbol<3)
        return(3);
//帐号少于三个字符

    if(at_symbol>19 )
        return(4);
//帐号多于十九个字符

    for(i=1;i<at_symbol;i++)
        if(char_test(str.charAt(i))==0 && spchar_test(str.charAt(i))==0)
            return (5);
    for(i=at_symbol+1;i<str.length;i++)
        if(char_test(str.charAt(i))==0 && spchar_test(str.charAt(i))==0)
            return (5);
//不能用其它的特殊字符

    for(i=at_symbol+1;i<str.length;i++)
        if(str.charAt(i)=='.') dot_symbol=i;
    for(i=at_symbol+1;i<str.length;i++)
        if(dot_symbol==0 || dot_symbol==str.length-1)
//简单的检测有没有“.”，以确定服务器名是否合法
            return (6);

    return (0);
//邮件名合法
}

/**
 * 手机号码格式验证
 * @param mobile
 * @returns {boolean}
 */
function validatemobile(mobile){   
    var myreg = /^1[0-9]{10}$/;//手机号码第二位不验证，确保长期有效
    //var myreg = /^(((13[0-9]{1})|(15[0-9]{1})|(17[0-9]{1})|(18[0-9]{1}))+\d{8})$/;
   if(!myreg.test(mobile)) 
   { 
       return false; 
   }
   return true; 
} 

/**
 * 判断ie和非ie浏览器
 * @returns {boolean}
 */
function is_ie(){
    if (!!window.ActiveXObject || "ActiveXObject" in window){   
                    //是  
        return true;       
        }else{  
        //不是   
         return false; 
    }
}

/**
 * 限制只能输入两位小数
 * @param obj
 * @constructor
 */
function Withdrawals(obj) {
    obj.value = obj.value.replace(/[^\d.]/g,"");
    obj.value = obj.value.replace(/\.{2,}/g,".");
    obj.value = obj.value.replace(".","$#$").replace(/\./g,"").replace("$#$",".");
    obj.value = obj.value.replace(/^(\-)*(\d+)\.(\d\d).*$/,'$1$2.$3');
    if(obj.value.indexOf(".")< 0 && obj.value !=""){
        obj.value= parseFloat(obj.value);
    }
}

/**
 * 限制只能输入4位小数
 * @param obj
 */
function lurpaks(obj) {
    obj.value = obj.value.replace(/[^\d.]/g,"");
    obj.value = obj.value.replace(/\.{2,}/g,".");
    obj.value = obj.value.replace(".","$#$").replace(/\./g,"").replace("$#$",".");
    obj.value = obj.value.replace(/^(\-)*(\d+)\.(\d\d\d\d).*$/,'$1$2.$3');//限制只能输入四位小数
    if(obj.value.indexOf(".")< 0 && obj.value !=""){
        obj.value= parseFloat(obj.value);
    }
}

/**
 * 验证邮政编码
 * @param value
 * @returns {boolean}
 * @constructor
 */
function TextBox(value) {
    var re= /^[1-9][0-9]{5}$/
    if(re.test(value))
        return true;
    else
    {
        return false;

    }
}

/**
 * 检查字符串是否为合法QQ号码
 * @param {String} 字符串
 * @return {bool} 是否为合法QQ号码
 */
function is_qq(aQQ) {
    var bValidate = RegExp(/^[1-9][0-9]{4,9}$/).test(aQQ);
    if (bValidate) {
        return true;
    }
    else
        return false;
}

/**
 * 验证银行卡号格式
 * @param BankNo
 */
function formatBankNo(BankNo){
    if (BankNo.value == "") return;
    var account = new String (BankNo.value);
    account = account.substring(0,22); /*帐号的总数, 包括空格在内 */
    if (account.match (".[0-9]{4}-[0-9]{4}-[0-9]{4}-[0-9]{7}") == null){
        /* 对照格式 */
        if (account.match (".[0-9]{4}-[0-9]{4}-[0-9]{4}-[0-9]{7}|" + ".[0-9]{4}-[0-9]{4}-[0-9]{4}-[0-9]{7}|" + ".[0-9]{4}-[0-9]{4}-[0-9]{4}-[0-9]{7}|" + ".[0-9]{4}-[0-9]{4}-[0-9]{4}-[0-9]{7}") == null){
            var accountNumeric = accountChar = "", i;
            for (i=0;i<account.length;i++){
                accountChar = account.substr (i,1);
                if (!isNaN (accountChar) && (accountChar != " ")) accountNumeric = accountNumeric + accountChar;
            }
            account = "";
            for (i=0;i<accountNumeric.length;i++){  /* 可将以下空格改为-,效果也不错 */
                if (i == 4) account = account + " "; /* 帐号第四位数后加空格 */
                if (i == 8) account = account + " "; /* 帐号第八位数后加空格 */
                if (i == 12) account = account + " ";/* 帐号第十二位后数后加空格 */
                account = account + accountNumeric.substr (i,1)
            }
        }
    }
    else
    {
        account = " " + account.substring (1,5) + " " + account.substring (6,10) + " " + account.substring (14,18) + "-" + account.substring(18,25);
    }
    if (account != BankNo.value) BankNo.value = account;
}

/**
 * 保留两位小数
 * @param x
 * @returns {*}
 */
function toDecimal2(x) {
    var f = parseFloat(x);
    if (isNaN(f)) {
        return false;
    }
    var f = Math.round(x*100)/100;
    var s = f.toString();
    var rs = s.indexOf('.');
    if (rs < 0) {
        rs = s.length;
        s += '.';
    }
    while (s.length <= rs + 2) {
        s += '0';
    }
    return s;
}

/**
 * 保留4位小数
 * @param x
 * @returns {*}
 */
function toDecimal4(x) {
    var f = parseFloat(x);
    if (isNaN(f)) {
        return false;
    }
    var f = Math.round(x*100)/100;
    var s = f.toString();
    var rs = s.indexOf('.');
    if (rs < 0) {
        rs = s.length;
        s += '.';
    }
    while (s.length <= rs + 4) {
        s += '0';
    }
    return s;
}

/**
 * 显示银行卡样式
 * @param obj
 */
function banktype(obj){
    var bankcard = '';
    var value = $('#bankcard_id option:selected').val();
    if(value == 0){
        bankcard = '';
        $('#bank').val(bankcard);
    }else{
        var str = obj.options[obj.selectedIndex].getAttribute('bankcard'); //卡号
        var name = obj.options[obj.selectedIndex].getAttribute('name'); //银行名称
        //组合成样式显示
        bankcard = str.substr(str.length-4);
        $('#bank').val(name+'****'+bankcard);
    }
}

/**
 * 显示银行卡样式2
 * @param obj
 */
function banktype2(obj){
    var bankcard = '';
    var value = $('#bankcard_id2 option:selected').val();
    if(value == 0){
        bankcard = '';
        $('#bank2').val(bankcard);
    }else {
        var str = obj.options[obj.selectedIndex].getAttribute('bankcard'); //卡号
        var name = obj.options[obj.selectedIndex].getAttribute('name'); //银行名称
        //组合成样式显示
        bankcard = str.substr(str.length - 4);
        $('#bank2').val(name + '****' + bankcard);
    }

}

/**
 * js 4进6出五成双算法
 * @param src
 * @param pos
 * @returns {number}
 * @constructor
 */
function PointFloat(src, pos) {

    return Math.round(src * Math.pow(10, pos)) / Math.pow(10, pos);
}

/**
 * 4进6出五成双算法
 * @param src
 * @param pos
 * @returns {*}
 */
function fomatFloat(src, pos) {

    var numArray, resultSymbol = "";
    if (src < 0) {
        resultSymbol = "-";
    }
    if (pos == "") {
        pos = new Number(0);
    }
    src = src.toString().replace("-", "");
    if (src.indexOf('.') > 0) {
        numArray = src.split('.');
        if (numArray[1].length > pos) {
            var endStr, isCarry=false;
            if (numArray[1].length > parseFloat(pos) + 1) {
                endStr = numArray[1].substring(parseFloat(pos) + 1);
                for (var i = 0; i < endStr.length; i++) {
                    if (endStr[i] > 0) {
                        isCarry = true;
                        break;
                    }
                }
            }
            numArray[1] = numArray[1].substring(0, pos + 1);
            var endChar = numArray[1][pos];
            var newpoint = new Number("0." + numArray[1].substring(0, pos));
            if (endChar >= 5 && pos >= 0) {
                if (endChar > 5) {
                    if (pos == 0) {
                        numArray[1] = 1;
                    }
                    else {
                        numArray[1] = parseFloat(newpoint) + parseFloat(Math.pow(0.1, pos));
                    }
                }
                else if (endChar == 5) {
                    //5后面有有效数字，直接向前进一位
                    if (isCarry) {
                        numArray[1] = parseFloat(newpoint) + parseFloat(Math.pow(0.1, pos));
                        return PointFloat(resultSymbol + eval(numArray.join("+")), pos);
                    }
                    if (pos == 0) {
                        if (numArray[0] % 2 != 0) {
                            numArray[1] = 1;
                        } else {
                            numArray[1] = 0;
                        }
                        return PointFloat(resultSymbol + eval(numArray.join("+")), pos);
                    }
                    var preChar = numArray[1][pos - 1];
                    if (preChar % 2 == 0) {
                        numArray[1] = newpoint;
                    }
                    else {
                        numArray[1] = parseFloat(newpoint) + parseFloat(Math.pow(0.1, pos));
                    }
                }
                return PointFloat(resultSymbol + eval(numArray.join("+")), pos);
            }
            else {
                numArray[1] = newpoint;
                return PointFloat(resultSymbol + eval(numArray.join("+")), pos);
            }
        }
        return src;

    } else {
        return resultSymbol + src;
    }
    return src;
}

/**
 * 获取用户详情
 * @param obj
 */
function getuser(obj){
    $.ajax({
        url:'/transaction/getuser',
        type:"post",
        data:{code:obj.value},
        dataType: "json",
        success:function(data){
            if(data.code == 1){
                //成功
                var html = '用户名：'+data.data.account+' <h3 class="name_xx"> 姓名：'+data.data.realname+'</h3>    手机号：'+data.data.mobile+''
                $('.cun_fen_xx').html(html);
                $('#is_account').val(0);
            }else{
                $('.cun_fen_xx').html('找不到用户信息，请核对！');
                $('#is_account').val(1);
                $('input[name=__token__]').val(data.token);
                //Lobibox.notify('error',{msg:data.msg,position: "bottom middle",delay: 2000,width: 400});
            }
        },
        error:function(e){
            //Lobibox.notify('error',{msg:'错误！！',position: "bottom middle",delay: 2000,width: 400});
        }
    });
}

/**
 * 检测姓名（中文）
 * @param v
 * @returns {number}
 */
function checkChineseName(v) {
    if (v == '') return 1; if (v.length < 2) { return 2; }
    if (v.length > 10) { return 2; }
    var name = v.replace(/·/g, ''); name = name.replace(/•/g, '');
    if(checkChinese(name))  return 0; else return 2;
}

/**
 * 检测用户名汉字
 * @param str
 * @returns {boolean}
 */
function checkChinese(str) {
    var re = /[^\u4e00-\u9fa5]/; if (re.test(str)) return false; return true;
}
// 申请调额页面s
/**
 * 选择申请调额方式
 * @param obj
 * @returns {boolean}
 */
function getdistributeParam(obj) {
    var type = $(obj).val();
    if(type == 0){
        var html = '<option value="0">请选择</option>';
        $('#paramid').html(html);
        $('#tishi').html('');
        return false;
    }
    $.ajax({
        url:'/transaction/getdistributeParam',
        type:"post",
        data:{type:type},
        dataType: "json",
        success:function(data){
            //成功
            var html = '<option value="0">请选择</option>';
            $.each(data, function(commentIndex, comment){
                var day_name = '不限';
                if(comment.day_name){
                    day_name = comment.day_name;
                }
                html += '<option value="'+comment.id+'" data2="'+day_name+'" data3="'+comment.name+'" data4="'+comment.name+'">'+comment.name+'</option>';
            });
            $('#paramid').html(html);

        },
        error:function(e){
            //Lobibox.notify('error',{msg:'错误！！',position: "bottom middle",delay: 2000,width: 400});
        }
    });
}

/**
 * 申请调额提示信息
 * @param obj
 * @returns {boolean}
 */
function getdistributeParams(obj) {
    var paramid = $(obj).val();
    if(paramid == 0){
        $('#tishi').html('');
        return false;
    }
    if(!$(obj).find("option:selected").attr("data2")){
        $('#tishi').html('不限');
    }else{
        $('#tishi').html($(obj).find("option:selected").attr("data2"));
    }
}

/**
 * 提示用已经输入了多少个字，还可以输入多少个字
 * @param message
 * @param remain
 * @constructor
 */
function CheckWorkCount(message, remain)
{
    var max;
    max = 200;
    if (message.value.length > max) {
        message.value = message.value.substring(0,max);
        remain.value = 0;
        jeBox.msg('不能超过200个字！', {icon: 6,time: 1});
    }
    else {
       document.getElementById(remain).innerHTML  = max - message.value.length;
        document.getElementById(remain+'2').innerHTML  = message.value.length;
    }
}

// 申请调额页面end

//手机注册页面
/**
 * 手机注册表单提交
 * @param form 表单id (VerificationCode时刷新验证码,pay时跳转支付页面)
 * @param url 表单提交地址
 * @param urls 表单提交成功后的跳转地址 默认或者空时刷新当前页面
 * @param msgs 表单提交成功后的提示信息 默认是“成功！”
 */
function submitMobile(form,url,urls,msgs){
    $.ajax({
        url:url,
        type:"post",
        data:$("#"+form).serialize(),
        dataType: "json",
        success:function(data){
            if(data.code == 1){
                //成功
                var messages = '成功！';
                if(msgs){
                    messages = msgs;
                }
                $.toast(messages);
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
            }else{
                $.toast(data.msg);
                if(form == 'VerificationCode'){
                    $("#yanzhengma").click();
                    $('.clare').val('');
                }

            }
        },
        error:function(e){
            $.toast('错误');
        }
    });
}

/**
 * 检测用户名
 * @param username
 * @returns {boolean}
 */
function checkUser(username)
{
    if (!username.match( /^[\u4E00-\u9FA5a-zA-Z][\u4E00-\u9FA5a-zA-Z0-9_]{5,11}$/)) {
        return false;
    }
    return true;
}

/**
 * 验证密码
 * @param str
 * @returns {boolean}
 */
function checkPwd(str)
{
    if(str.length < 8 || str.length > 20 || /^\d+$/.test(str)){
        return false;
    }
    // if(/^\d+$/.test(str)) {
    //     return false;
    // }
    return true;
}

/**
 * 验证验证码格式
 * @param str
 * @returns {boolean}
 */
function checkCode(str)
{
    if (!str.match( /^[a-zA-Z0-9_]{4,6}$/)) {
        return false;
    }
    return true;
}

/**
 * 验证公司名称
 * @param company
 * @returns {boolean}
 */
function checkCompany(company)
{

    if (!company.match( /^[\u4E00-\u9FA5a-zA-Z0-9]{6,16}$/)) {
        return false;
    }
    return true;
}

/**
 * 验证营业执照
 * @param busCode
 * @returns {boolean}
 */
function isValidBusCode(busCode){
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
}


/**
 * 验证6位安全数字密码不能太简单（111111,123456,654321）
 * @param s
 * @returns {boolean}
 */
function securityPwd(s) {
    if (!/^\d{6}$/.test(s)) return false; // 不是6位数字
    if (/^(\d)\1+$/.test(s)) return false;  // 全一样

    var str = s.replace(/\d/g, function($0, pos) {
        return parseInt($0)-pos;
    });
    if (/^(\d)\1+$/.test(str)) return false;  // 顺增

    str = s.replace(/\d/g, function($0, pos) {
        return parseInt($0)+pos;
    });
    if (/^(\d)\1+$/.test(str)) return false;  // 顺减
    return true;
}

/*
* 提现页面start
 */
/**
 * 获取下拉
 * @param obj
 */
function dj(obj){
    if($(this).parent().hasClass('slist_')){
        $(this).parent().removeClass('slist_');
    }else{
        $(this).parent().addClass('slist_');
    }
}
/*
* 银宝提现提交
 */
$('.lurpak_withdrawals').click(function () {
    if($('#bankcard_id2').val() == ''){
        jeBox.msg('请选择收款卡号！', {icon: 6,time: 2});
        return false;
    }
    if($('#tiyb').val() == 0){
        jeBox.msg('请输入提现云积分！', {icon: 6,time: 2});
        return false;
    }else{
        if(isNaN($('#tiyb').val())){
            jeBox.msg('提现云积分格式错误！', {icon: 6,time: 2});
            return false;
        }
    }

    if($('#safe_psw2').val() == ''){
        jeBox.msg('请输入安全密码！', {icon: 6,time: 2});
        return false;
    }else{
        if(!/^[0-9]*[1-9][0-9]*$/.test($('#safe_psw2').val())){
            jeBox.msg('安全密码格式错误！', {icon: 6,time: 2});
            return false;
        }
        if($('#safe_psw2').val().length != 6){
            jeBox.msg('安全密码长度为6位！', {icon: 6,time: 2});
            return false;
        }
    }
    submit2('tf2','/transaction/lurpaks','/transaction/lurpak_withdrawals','提交成功，等待处理！','lurpak_withdrawals');
});
/*
* 现金提现提交
 */
$('.cash_withdrawals').click(function () {
    if($('#bankcard_id').val() == ''){
        jeBox.msg('请选择收款卡号！', {icon: 6,time: 2});
        return false;
    }
    if($('#tiyb2').val() == 0){
        jeBox.msg('请输入提现金额！', {icon: 6,time: 2});
        return false;
    }else{
        if(isNaN($('#tiyb2').val())){
            jeBox.msg('提现金额格式错误！', {icon: 6,time: 2});
            return false;
        }
    }

    if($('input[name=safe_psw]').val() == ''){
        jeBox.msg('请输入安全密码！', {icon: 6,time: 2});
        return false;
    }else{
        if(!/^[0-9]*[1-9][0-9]*$/.test($('input[name=safe_psw]').val())){
            jeBox.msg('安全密码格式错误！', {icon: 6,time: 2});
            return false;
        }
        if($('input[name=safe_psw]').val().length != 6){
            jeBox.msg('安全密码长度为6位！', {icon: 6,time: 2});
            return false;
        }
    }
    submit2('tf','/transaction/withdrawalss','/transaction/cash_withdrawals','提交成功，等待处理！','cash_withdrawals');
});
/*
* 提现页面end
 */

/*
* 充值页面start
 */
$('.recharges').click(function () {
    if($('input[name=money]').val() == ''){
        jeBox.msg('请输入充值金额！', {icon: 6,time: 2});
        return false;
    }
    // if(!/^[0-9]*[1-9][0-9]*$/.test($('input[name=money]').val())){
    //     jeBox.msg('充值金额格式不正确！', {icon: 6,time: 2});
    //     return false;
    // }
    submit('tf','/transaction/recharges','/transaction/recharge_pay/code/');
});

/*
* 充值页面end
 */

/*
* 购买充值金额页面start
 */
$('.purchaseStockintegralHandle').click(function () {
    if($('input[name=money]').val() == ''){
        jeBox.msg('请输入购买金额！', {icon: 6,time: 2});
        return false;
    }

    if($('input[name=money]').val() <= 0){
        jeBox.msg('购买金额格式不正确！', {icon: 6,time: 2});
        return false;
    }
    // if(!/^[0-9]*[1-9][0-9]*$/.test($('input[name=money]').val())){
    //     jeBox.msg('购买金额格式不正确！', {icon: 6,time: 2});
    //     return false;
    // }
    submit2('pay','/transaction/purchaseStockintegralHandle','/transaction/createForm/code/','提交成功，跳转支付中！','purchaseStockintegralHandle');
});
/*
* 购买充值金额end
 */

/*
* 积分分发页面star
 */

$('.integral_distributionsHandle').click(function () {
    if($('.cun_fen_user').val() == ''){
        jeBox.msg('请输入买家账号！', {icon: 6,time: 2});
        return false;
    }else{
        if($('#is_account').val() == 0){
            jeBox.msg('买家账号不存在，请核对！', {icon: 6,time: 2});
            return false;
        }
        if($('#is_account').val() == 2){
            jeBox.msg('您不能输入自己的账号！', {icon: 6,time: 2});
            return false;
        }
    }

    if($('input[name=money]').val() == ''){
        jeBox.msg('请输入消费金额！', {icon: 6,time: 2});
        return false;
    }else{
        if(!/^[0-9]*[1-9][0-9]*$/.test($('input[name=money]').val())){
            jeBox.msg('消费金额格式不正确！', {icon: 6,time: 2});
            return false;
        }
    }
    if($('#is_quota').val() == 1){
        var images = false;
        $("input[name='picture[]']").each(function(j,item){
            if(item.value){
                images = true;
            }
            console.log(item.value);
        });

        if(!images){
            jeBox.msg('调额证明图片至少上传一张！', {icon: 6,time: 2});
            return false;
        }
    }

    if($('input[name=safe_psw]').val() == ''){
        jeBox.msg('请输入安全密码！', {icon: 6,time: 2});
        return false;
    }else{
        if(!/^[0-9]*[1-9][0-9]*$/.test($('input[name=safe_psw]').val())){
            jeBox.msg('安全密码格式错误！', {icon: 6,time: 2});
            return false;
        }
        if($('input[name=safe_psw]').val().length != 6){
            jeBox.msg('安全密码长度为6位！', {icon: 6,time: 2});
            return false;
        }
    }

    submit2('tf','/transaction/integral_distributionsHandle','/transaction/integral_distribution','提交成功！','integral_distributionsHandle');
});

$('.no_dui2').click(function(){
    var type = $(this).attr('data-action');
    if(type == 1){
        $('.demo-images-in').css('display','');
    }else{
        $('.demo-images-in').css('display','none');
    }
    $('.no_dui2').removeClass('dui');
    $(this).addClass('dui');
});

/**
 * 限制只能输入数字
 * @param obj
 * @param max
 */
function num_checkeds(obj,max){
    //48-57 96-105（数字键盘） 8 9(8为回格键，9为Tab键)
    var event=arguments.callee.caller.arguments[0]||window.event;//消除浏览器差异
    var code = event.keyCode;
    obj.value= obj.value.replace(/\D/g,'');
    //if((code >= 48 && code <= 57) || (code >= 96 && code <= 105) || code == 8 || code == 9){
        //输入的是数字，通过
        if($('#is_quota').val() == 0){
            if(obj.value > 1000000){
                obj.value = 1000000;
                jeBox.msg('超过分发库存积分(1000000)上限！！', {icon: 6,time:2});
            }else{
                if(obj.value > max){
                    obj.value = max;
                    jeBox.msg('超过分发库存积分('+max+')上限！！', {icon: 6,time:2});
                }
            }

        }
        var reward_ratio =  $('#reward_ratio option:selected') .val();//选中的值
        //计算库存积分 金额*100*倍数
        var stock = Number(reward_ratio) * Number(obj.value) * Number(100);

        $('.update').html(stock.toFixed(2));

    //     event.returnValue= true;
    // }else{
    //     event.returnValue= false;
    //     return false;
    // }
}

/**
 * 充值限制金额输入
 * @param obj
 * @param max
 */
function RechargeMoneyCheckeds(obj,max){
    //48-57 96-105（数字键盘） 8 9(8为回格键，9为Tab键)
    var event=arguments.callee.caller.arguments[0]||window.event;//消除浏览器差异
    var code = event.keyCode;
    obj.value= obj.value.replace(/\D/g,'');
    //if((code >= 48 && code <= 57) || (code >= 96 && code <= 105) || code == 8 || code == 9){
    //输入的是数字，通过

        if(obj.value > max){
            obj.value = max;
            jeBox.msg('超过单次充值金额('+max+')上限！！', {icon: 6,time:2});
        }

}

/**
 * 判断是否超过购买库存积分限制
 * @param obj
 * @param max
 */
function is_quota(obj,max) {
    var data = $(obj).attr('data-action');
    document.getElementById('is_quota').value = data;
    if(data == 0){
        if($('#money').val() > max){
            $('#money').val(max);
            var reward_ratio =  $('#reward_ratio option:selected') .val();//选中的值
            //计算库存积分 金额*100*倍数
            var stock = Number(reward_ratio) * Number(max) * Number(100);
            $('.update').html(stock.toFixed(2));
            jeBox.msg('超过分发库存积分('+max+')上限！！', {icon: 6,time:2});
        }
        $('.tishis').css('display','none');
    }else if(data == 1){
        $('.tishis').css('display','');
    }
}

/**
 * 获取用户详情
 * @param obj
 * @param account
 * @returns {boolean}
 */
function getuser3(obj,account){
    if(obj.value == account){
        $('#is_account').val(2);
        $('.cun_fen_xx').html('不能输入自己的账号！');
        return false;
    }
    $.ajax({
        url:'/transaction/getuser',
        type:"post",
        data:{code:obj.value},
        dataType: "json",
        success:function(data){
            if(data.code == 1){
                //成功
                var html = '用户名：'+data.data.account+' <h3 class="name_xx"> 姓名：'+data.data.realname+'</h3>    手机号：'+data.data.mobile+''
                $('.cun_fen_xx').html(html);
                $('.cun_fen_btn').css('background','#40abf3');
                $('.cun_fen_btn').css('color','#fff');
                $('#is_account').val(1);

            }else{
                $('input[name=__token__]').val(data.token);
                $('.cun_fen_xx').html('找不到此账户，请核对！');
                $('.cun_fen_btn').css('background','#d8d8d8');
                $('.cun_fen_btn').css('color','#fff');
                $('#is_account').val(0);
            }
        },
        error:function(e){
        }
    });
}
/*
* 积分分发end
 */
