/**
 * 生成分页js
 * @param id
 * @param type
 */
function page_html(id,type){
    $.ajax({
        type: "POST",
        url: $("#url").val(),
        data: $("#"+id).serialize(),
        dataType: "json",
        //async: false,
        success: function (data) {
            if(data.code == 1){
                var html = template('test', data.data);
                document.getElementById('order_lists').innerHTML = html;

            }else{
                html = '<tr><th colspan="7"><div>没有找到记录!</div></th></tr>';
                document.getElementById('order_lists').innerHTML = html;
            }
        },
        error: function (msg) {
            jeBox.msg('错误！', {icon: 6});
        }
    });
}

/**
 * 查看详情
 * @param obj
 */
function xiangqing(obj){
    var id = obj.attr('data');
    $.ajax({
        type: "post",
        url: $("#xiangqing").val(),
        data: {id:id},
        dataType: "json",
        beforeSend: function(){
            $('.spinner').css('display','');
        },
        success: function (data) {
            $('.spinner').css('display','none');
            var html = '';
            if(data.code == 1){
                $('#order_details').html(details(data.data));
            }else{
                jeBox.msg(data.msg, {icon: 6});
            }
            return false;
        },
        error: function (msg) {
            jeBox.msg('错误！', {icon: 6});
        }
    });

}

/**
 * 生成html详情
 * @param data
 * @returns {string}
 */
function details(data) {
    var html = ' <div class="form-group mb0">';
    html += '<label class="col-sm-3 pl0 text-right small_xs fs14">编号：</label>';
    html += '<div class="col-sm-8 pl0 line20 fs14">  '+data.id+'  </div>';
    html += '</div>';
    html += '<div class="form-group mb0">';
    html += '<label class="col-sm-3 pl0 text-right small_xs fs14">创建时间：</label>';
    html += '<div class="col-sm-8 pl0 line20 fs14">  '+data.create_time+'   </div>';
    html += '</div>';
    html += '<div class="form-group mb0">';
    html += '<label class="col-sm-3 pl0 text-right small_xs fs14">工单类型：</label>';
    html += '<div class="col-sm-8 pl0 line20 fs14"> '+data.typeName+'  </div>';
    html += '</div>';
    html += '<div class="form-group mb0">';
    html += '<label class="col-sm-3 pl0 text-right small_xs fs14">标题：</label>';
    html += '<div class="col-sm-8 pl0 line20 fs14"> '+data.title+'  </div>';
    html += '</div>';
    if(data.new_value){
    html += '<div class="form-group mb0">';
    html += '<label class="col-sm-3 pl0 text-right small_xs fs14">修改值：</label>';
    html += '<div class="col-sm-8 pl0 line20 fs14"> '+data.new_value+'  </div>';
    html += '</div>';
    }
    if(data.old_value){
    html += '<div class="form-group mb0">';
    html += '<label class="col-sm-3 pl0 text-right small_xs fs14">原值：</label>';
    html += '<div class="col-sm-8 pl0 line20 fs14"> '+data.old_value+'  </div>';
    html += '</div>';
    }
    var photo1 = '';
    if(data.photo1){
        var photo1 = '<a href="'+data.photo1+'" target="_blank">查看</a>';
    }
    html += '<div class="form-group mb0">';
    html += '<label class="col-sm-3 pl0 text-right small_xs fs14">申请凭证：</label>';
    html += '<div class="col-sm-8 pl0 line20 fs14"> <a href="'+data.photo0+'" target="_blank">查看</a>'+photo1+'</div>';
    html += '</div>';
    html += '<div class="form-group mb0">';
    html += '<label class="col-sm-3 pl0 text-right small_xs fs14">状态：</label>';
    html += '<div class="col-sm-8 pl0 line20 fs14"> '+data.dealType+'  </div>';
    html += '</div>';
    html += '<div class="form-group mb0">';
    html += '<div class="col-sm-2 col-xs-offset-5 pl0 line20 fs14">';
    html += '<div  class="btn btn-block btn-sm btn_blue" data-dismiss="modal">关闭</div>';
    html += '</div>';
    html += '</div>';

    return html;
}

