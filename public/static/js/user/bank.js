/**
 * 获取城市
 * @param obj
 * @param type
 * @returns {boolean}
 */
function change(obj,type){
        var id = $('#province').find('option:selected').attr('data-action');//obj.options[obj.selectedIndex].value;
        var name;
        var names;
        if(type == 1){
            //获取城市
            $(".city").empty();
            name ='city';
            names = '请选择城市';
        }else if(type == 2){
            //获取县区
            name ='district';
            $(".district").empty();
            $(".town").empty();
            names = '请选择区县';
        }else{
            //不需要获取
            return false;
        }
        if(id == 0){
            $(".city").html('<option value="0">'+names+'</option>');
            return false;
        }
        $.ajax({
            type: "POST",
            url: "/userdata/getCity",
            data: {id:id},
            dataType: "json",
            success: function(data){
                if(data.code == '1'){
                    $('.'+name).empty();   //清空resText里面的所有内容
                    var html = '<option value="">'+names+'</option>';
                    $.each(data.data, function(commentIndex, comment){
                        html += '<option value="'+comment.a_name+'">'+comment.a_name+'</option>';
                    });
                    $('.'+name).html(html);
                }else if(data.code == '3'){

                    $('.'+name).html('<option> 暂无选项</option>');
                }
            }
        });

    }


/**
 * 表单提交
 * @param url
 * @returns {boolean}
 */
    function font(url){
        if($("#bank_id").val() == 0){
            jeBox.msg('请选择银行！', {icon: 6,time: 3});
            return false;
        }
        var bankcard = $("input[name=bankcard]").val();
        if(bankcard == ''){
            jeBox.msg('请输入银行卡号！', {icon: 6,time: 3});
            return false;
        }
        if(bankcard.length < 15 ) {
            jeBox.msg('银行卡号长度不正确！', {icon: 6,time: 3});
            return false;
        }
        if($(".province").val() == 0){
            jeBox.msg('请选择省份！', {icon: 6,time: 3});
            return false;
        }
        if($(".city").val() == 0){
            jeBox.msg('请选择城市！', {icon: 6,time: 3});
            return false;
        }
        if($("input[name=branch]").val() == ''){
            jeBox.msg('请输入银行卡开户行！', {icon: 6});
            return false;
        }
        submit2('tf',url,"/userdata/bankcard",'提交成功！','btn_');
    }