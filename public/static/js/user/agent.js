/**
 * 代理地区选择
 * @param obj
 * @param type
 * @returns {boolean}
 */
function change(obj,type){
    var id = obj.options[obj.selectedIndex].value;
    var data = obj.options[obj.selectedIndex].getAttribute('data');
    var data2 = obj.options[obj.selectedIndex].getAttribute('data2');
    var data3 = obj.options[obj.selectedIndex].getAttribute('data3');

    var ea_reg_num = obj.options[obj.selectedIndex].getAttribute('ea_reg_num');//ea已经申请的人数
    var ea_num = obj.options[obj.selectedIndex].getAttribute('ea_num');  //ea 允许申请的人数
    var ga_reg_num = obj.options[obj.selectedIndex].getAttribute('ga_reg_num'); //ga已经申请的人数
    var ga_num = obj.options[obj.selectedIndex].getAttribute('ga_num'); // ga允许申请的人数
    if(ea_reg_num != '' && ea_num != '' && Number(ea_reg_num) == Number(ea_num)){
        //ea 不允许申请了
        document.getElementById('ea_num').value = 1;
    }

    if(ga_reg_num != '' && ga_num != '' && Number(ga_reg_num) == Number(ga_num)){
        //ga 不允许申请了
        document.getElementById('ga_num').value = 1;
    }
    document.getElementById('is_agent').value = 0;
    //判断当前地区代理是否满了，满了提示不允许申请
    if(data == ''){
        data = '该地区不存在代理';
        document.getElementById('is_agent').value = 1;
    }
    if(data2 == ''){
        data2 = 0;
    }
    if(data3 == ''){
        data3 = 0;
    }

    document.getElementById('type').value = data;
    document.getElementById('ga_standard').value = data2;
    document.getElementById('supervision_standard').value = data3;
    var name;
    var names;
    if(type == 1){
        //获取城市
        $(".city").empty();
        $(".district").html('<option value="0">请选择区县</option>');
        $(".town").html('<option value="0">请选择乡镇或街道</option>');
        name ='city';
        names = '请选择城市';
    }else if(type == 2){
        //获取县区
        name ='district';
        $(".district").empty();
        $(".town").empty();
        names = '请选择区县';
        $(".town").html('<option value="0">请选择乡镇或街道</option>');
    }else if(type == 3){
        //获取街道乡镇
        name ='town';
        names = '请选择乡镇或街道';
        $(".town").empty();

    }else{
        //不需要获取
        return false;
    }
    if(!id){
        $(".city").html('<option value="">'+names+'</option>');
        return false;
    }
    if(id == 0){
        $("."+name).html('<option value="">'+names+'</option>');
        return false;
    }
    $.ajax({
        type: "POST",
        url: "/agent/getcity",
        data: {id:id},
        dataType: "json",
        success: function(data){
            if(data.code == '1'){
                $('.'+name).empty();   //清空resText里面的所有内容
                var html = '<option value="0">'+names+'</option>';
                $.each(data.data, function(commentIndex, comment){
                    var ga_standard = '';
                    var level_name = '';
                    var ga_num = '';
                    var ea_num = '';
                    var ga_reg_num = '';
                    var ea_reg_num = '';
                    if(comment.agent_level.level_name){
                        level_name = comment.agent_level.level_name;
                    }
                    if(comment.agent_level.ga_standard){
                        ga_standard = comment.agent_level.ga_standard;
                    }

                    if(comment.agent_level.ga_num){
                        ga_num = comment.agent_level.ga_num;
                    }

                    if(comment.agent_level.ga_reg_num){
                        ga_reg_num = comment.agent_level.ga_reg_num;
                    }

                    if(comment.agent_level.ea_num){
                        ea_num = comment.agent_level.ea_num;
                    }

                    if(comment.agent_level.ea_reg_num){
                        ea_reg_num = comment.agent_level.ea_reg_num;
                    }
                    html += '<option value="'+comment.id+'" ea_reg_num="'+ea_reg_num+'" ea_num="'+ea_num+'" ga_reg_num="'+ga_reg_num+'" ga_num="'+ga_num+'"  data="'+level_name+'" data2="'+ga_standard+'" data3="'+comment.agent_level.supervision_standard+'">'+comment.a_name+'</option>';
                });
                $('.'+name).html(html);

            }else if(data.code == '3'){

                $('.'+name).html('<option value=""> 暂无选项</option>');
            }
        }
    });

}

/**
 * 获取职位价格
 * @param obj
 */
function job(obj) {
    var type = obj.options[obj.selectedIndex].value;
    $.ajax({
        type: "POST",
        url: "/agent/getjob",
        data: {type:type},
        dataType: "json",
        success: function(data){
            if(data.code == '1'){
                $('.job').empty();   //清空resText里面的所有内容
                var html = '<option value="">请选择职位</option>';
                $.each(data.data, function(commentIndex, comment){

                    html += '<option value="'+comment.id+'" data="'+comment.income_ratio+'" data2="'+comment.standard_ratio+'">'+comment.job+'</option>';
                });
                $('.job').html(html);

            }else{
                jeBox.msg('职位不存在！', {icon: 6});
            }
        }
    });
}

/**
 * 获取职位价格
 * @param obj
 */
function job2(obj) {
    var type = obj.options[obj.selectedIndex].value;
    var pp = document.getElementById('ga_standard').value;
    var job_pre =  obj.options[obj.selectedIndex].getAttribute('data2');
    if(type == 18){
        //职位是监理的时候执行另外一种价格
        pp = document.getElementById('supervision_standard').value;
    }

    //价格
    var preice = Number(job_pre) * Number(pp);
    document.getElementById('preice').value = preice;
}

$('.submit2').click(function () {
    if($("#is_agent").val() == 1){
        jeBox.msg('当前地区不存在代理，请切换其他地区申请！', {icon: 6});
        return false;
    }
    if($('#typess option:selected') == 'EA'){
        if($("#ea_num").val() == 1){
            jeBox.msg('当前地区所选职位类别(ZD)代理人数已经饱和，请切换其他地区或其他职位类型申请！', {icon: 6});
            return false;
        }
    }else if($('#typess option:selected') == 'GA'){
        if($("#ga_num").val() == 1){
            jeBox.msg('当前地区所选职位类别(LD)代理人数已经饱和，请切换其他地区或其他职位类型申请！', {icon: 6});
            return false;
        }
    }
    if($('#province option:selected') .val() == ''){
        jeBox.msg('请选择省份！', {icon: 6});
        return false;
    }
    if($('#typess option:selected') == 0){
        jeBox.msg('请选择职位类别！', {icon: 6});
        return false;
    }
    if($('#job_id option:selected') == 0){
        jeBox.msg('请选择职位！', {icon: 6});
        return false;
    }

    if($('input[name=code]').val() == ''){
        jeBox.msg('请输入验证码！', {icon: 6});
        return false;
    }
    //刷新验证码
    submit2('VerificationCode',"/agent/cate_purchase","/agent/index","申请成功，请耐心等待审核！","submit2");
})

// 购买代理申请end


//购买代理付款
$('.set2_mi').click(function () {
    if($('#bank_id option:selected') .val() == ''){
        jeBox.msg('请选择银行！', {icon: 6});
        return false;
    }

    var name = $('input[name=name]').val();
    if(name == ''){
        jeBox.msg('请输入户名！', {icon: 6});
        return false;
    }else{
        if(checkChineseName(name) == 2){
            jeBox.msg('户名只能是中文汉字，最少两个汉字，最多10个汉字！', {icon: 6});
            return false;
        }
    }

    var bankcard = $('input[name=bankcard]').val();
    if(bankcard == ''){
        jeBox.msg('请输入银行卡号！', {icon: 6});
        return false;
    }else{
        if(bankcard.length < 19 || bankcard.length > 22){
            jeBox.msg('卡号格式不正确！', {icon: 6});
            return false;
        }

    }

    if($("#voucher_pic").val() == 0 && $("#voucher_pic2").val() == 0 && $("#voucher_pic3").val() == 0){
        jeBox.msg('转账凭证至少上传一张图片！', {icon: 6});
        return false;
    }
    submit2('tf','/agent/pay_agents','/agent/index','提交成功，请耐心等待审核！','set2_mi');
});
//购买代理付款end

$('.submit').click(function () {
    if($("#code").val() == ''){
        jeBox.msg('请输入代理转让订单号！', {icon: 6});
        return false;
    }

    if($("#to_account").val() == ''){
        jeBox.msg('请输入接收方账号！', {icon: 6});
        return false;
    }else{
        if($('#is_account').val() == 1){
            jeBox.msg('接收账号不存在，请重新输入！', {icon: 6});
            return false;
        }
    }

    if($('#money').val() == ''){
        jeBox.msg('请输入转让价格！', {icon: 6});
        return false;
    }

    if($('input[name=codes]').val() == ''){
        jeBox.msg('请输入验证码！', {icon: 6});
        return false;
    }
    //刷新验证码
    submit2('VerificationCode','/agent/transferagents','/agent/transferagentlist','提交成功，等待审核！','submit');
})
// 代理转让end