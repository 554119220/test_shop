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
            beforeSend: function(){
                $('.spinner').css('display','');
                },
            success: function (data) {
                $('.spinner').css('display','none');
                var html = '';
                if(data.code == 1){
                    $('.share').empty();   //清空resText里面的所有内容

                     html = successs(data,type);

                     $('.share').html(html);
                     $("#page").html(data.page_html);
                }else if(data.code == 5){
                    jeBox.msg(data.msg, {icon: 6});
                }else{
                    var col = 7;
                    if(document.getElementById('action').value == 'Agent'){
                        col = 8;
                    }

                    if(type == 'transferagentlist' || type == 'transferagentslist'){
                        col = 9;
                    }

                    html += '<tr><th colspan="'+col+'"><div>没有找到记录!</div></th></tr>';
                    $('.share').html(html);
                    $("#page").html('');

                }
            },
            error: function (msg) {
                jeBox.msg('错误！', {icon: 6});
            }
        });
}


/**
 * 返回信息处理
 * @param data
 * @param type
 * @returns {*}
 */
 function successs(data,type) {
            var html;

            if(type == 'index'){
                 if(document.getElementById('action').value == 'Agent'){
                     type = document.getElementById('action').value;
                 }

                if(document.getElementById('action').value == 'Workorder'){
                    type = document.getElementById('action').value;
                }
            }
              switch(type){
                case 'cash':
                    html = cash(data.data.list);//现金账户记录
                break;
                case 'integral':
                    html = account(data.data.list);//积分账户记录
                break;
                case 'lurpak':
                    html = account(data.data.list);//拉伸银宝价值贡献记录
                break;
                case 'commission':
                    html = account(data.data.list);//提现手续费抵押金记录
                break;
                case 'project':
                    html = account(data.data.list);//大项目抵押金记录
                break;
                case 'consumption':
                    html = account(data.data.list);//消费银宝账户记录
                break;
                case 'lurpaks':
                    html = account(data.data.list);//银宝账户记录
                break;
                case 'stock':
                    html = account(data.data.list);//库存积分账户记录
                break;
                case 'management':
                    html = account(data.data.list);//统一管理奖记录
                break;
                case 'index':
                    html = recharge(data.data.list);//充值记录
                break;
                case 'lurpak_withdrawals':

                    html = lurpak_withdrawals(data.data.list);//银宝提现记录
                break;
                case 'cash_withdrawals':

                    html = cash_withdrawals(data.data.list);//现金提现记录
                break;

                case 'purchase_integral':
                    html = purchase_integral(data.data.list); //购买库存积分记录
                break;
                case 'integral_distribution':
                    html = integral_distribution(data.data.list); //积分分记录（买家）
                break;

                case 'stock_integral_distribution':
                    html = stock_integral_distribution(data.data.list); //积分分记录（卖家）
                break;
                case 'line_transaction':
                    html = line_transaction(data.data.list); //线下交易
                break;
                case 'lurpaktransfer':
                    html = lurpaktransfer(data.data.list); //银宝转账
                break;
                case 'vipcommission':
                  html = account(data.data.list); //vip账户提成
               break;
               case 'Workorder':
                html = index(data.data.list); //工单中心首页
                break;
              case 'adjust_purchase_integral_list':
              html = adjust_purchase_integral(data.data); //调额申请记录
                break;
              case 'Agent':
                  html = agentlist(data.data); //代理记录
                  break;
              case 'transferagentlist':
                  html = transferagentlist(data.data); //代理转让记录
                  break;
              case 'transferagentslist':
                  html = transferagentslist(data.data); //接收的代理转让记录
                  break;
              }
        return html;
    }

    //调额申请记录
      function adjust_purchase_integral(data) {
          var html = '';
          $.each(data, function(commentIndex, comment){
              html += '<tr><td>'+comment.code+'</td>';
              html += '<td>'+comment.confirm_date+'</td>';
              html += '<td>'+comment.slimit+'</td>';
              html += '<td>'+comment.dlimit+'</td>';
              html += '<td>'+comment.type_name+'</td>';
              html += '<td>'+comment.status_name+'</td>';

              html += '<td><a href="javascript:;" onclick="xiangqing($(this));"  data-toggle="modal" data-target="#myModal" data="'+comment.code+'">查看详情</a></td>';
              html += '</tr>';
          });
          return html;
    }

    //代理记录
    function agentlist(data) {
        var html = '';
        $.each(data.list, function(commentIndex, comment){
            html += '<tr><td>'+comment.code+'</td>';
            html += '<td>'+comment.create_time+'</td>';
            html += '<td>'+comment.level_name+'</td>';
            html += '<td>'+comment.job_type+'</td>';
            html += '<td>'+comment.job_name+'</td>';
            html += '<td>'+comment.province_name+comment.city_name+comment.district_name+comment.town_name+'</td>';
            html += '<td>'+comment.status_name+'</td>';
            var pay = '';
            if(comment.status == 2){
                pay += '<a class="btn_1" href="/agent/pay_agent/code/'+comment.code+'/money/'+comment.money+'">付款</a>';
            }else if(comment.status == 5){
                pay += '<a class="btn_1" href="/agent/transferagent/code/'+comment.code+'">转让</a>';
            }
            html += '<td><a href="/agent/agent_details2/code/'+comment.code+'">查看详情</a>  '+pay+'</td>';
            html += '</tr>';
        });
        return html;
    }

    //代理转让记录
    function transferagentlist(data) {
        var html = '';
        $.each(data.list, function(commentIndex, comment){
            html += '<tr><td>'+comment.code+'</td>';
            html += '<td>'+comment.create_time+'</td>';
            html += '<td>'+comment.level_name+'</td>';
            html += '<td>'+comment.job_type+'</td>';
            html += '<td>'+comment.job_name+'</td>';
            html += '<td>'+comment.money+'</td>';
            html += '<td>'+comment.province_name+comment.city_name+comment.district_name+comment.town_name+'</td>';
            html += '<td>'+comment.status_name+'</td>';
            var pay = '';
            if(comment.status == 1){
                pay += '<a class="btn_1" href="/agent/pay_transferagent/code/'+comment.code+'/money/'+comment.money+'">付款</a>';
            }
            html += '<td><a href="/agent/transferagent_details2/code/'+comment.code+'">查看详情</a>  '+pay+'</td>';
            html += '</tr>';
        });
        return html;
    }

//代理转让记录
function transferagentslist(data) {
    var html = '';
    $.each(data.list, function(commentIndex, comment){
        html += '<tr><td>'+comment.code+'</td>';
        html += '<td>'+comment.create_time+'</td>';
        html += '<td>'+comment.level_name+'</td>';
        html += '<td>'+comment.job_type+'</td>';
        html += '<td>'+comment.job_name+'</td>';
        html += '<td>'+comment.money+'</td>';
        html += '<td>'+comment.province_name+comment.city_name+comment.district_name+comment.town_name+'</td>';
        html += '<td>'+comment.status_name+'</td>';
        html += '<td><a href="/agent/transferagents_details2/code/'+comment.code+'">查看详情</a></td>';
        html += '</tr>';
    });
    return html;
}

    //工单中心首页
    function index(data) {
        var html = '';
        $.each(data, function(commentIndex, comment){
            html += '<tr><td>'+comment.id+'</td>';
            html += '<td>'+comment.create_time+'</td>';
            html += '<td>'+comment.typeName+'</td>';
            html += '<td>'+comment.title+'</td>';
            html += '<td>'+comment.dealState+'</td>';
            html += '<td><a href="javascript:;" onclick="xiangqing($(this));"  data-toggle="modal" data-target="#myModal" data="'+comment.id+'">查看详情</a></td>';
            html += '</tr>';
        });
        return html;
    }
    //现金账户信息
    function cash(data){
        var html = '';
        $.each(data, function(commentIndex, comment){                                               
                html += '<tr><td>'+comment.code+'</td>';
                html += '<td>'+comment.create_time+'</td>';
                var style = 'text_green';
                if(comment.flow_direction == 1){
                    style = 'text_yellow';
                }
                html += '<td class="'+style+'">'+comment.flow_direction_name+'</td>';
                html += '<td>'+comment.trade_type_name+'</td>';
                html += '<td>'+comment.income_expenses+'</td>';
                if(comment.memo == null){
                    comment.memo = '无';
                }
                html += '<td>'+comment.memo+'</td>';
                html += '<td><a href="javascript:;" onclick="xiangqing($(this));"  data-toggle="modal" data-target="#myModal" data="'+comment.code+'">查看详情</a></td>';
                html += '</tr>';
             });
        return html;
    }

    //银宝转账
    function lurpaktransfer(data){
        var html = '';
        $.each(data, function(commentIndex, comment){                                               
                html += '<tr><td>'+comment.code+'</td>';
                html += '<td>'+comment.create_time+'</td>';
                var style = 'text_green';
                if(comment.flow_direction == 1){
                    style = 'text_yellow';
                }
                html += '<td class="'+style+'">'+comment.lurpak+'</td>';
                html += '<td>'+comment.arrival_lurpak+'</td>';
                html += '<td>'+comment.pay_poundage_lurpak+'</td>';
                html += '<td>'+comment.to_user.account+'</td>';
                html += '<td><a href="javascript:;" onclick="xiangqing($(this));"  data-toggle="modal" data-target="#myModal" data="'+comment.code+'">查看详情</a></td>';
                html += '</tr>';
             });
        return html;
    }


    //账户信息异动信息
    function account(data){
        var html = '';
        $.each(data, function(commentIndex, comment){                                               
                html += '<tr><td>'+comment.code+'</td>';
                html += '<td>'+comment.create_time+'</td>';
                var style = 'text_green';
                if(comment.flow_direction == 1){
                    style = 'text_yellow';
                }
                html += '<td class="'+style+'">'+comment.flow_direction_name+'</td>';
                html += '<td>'+comment.trade_type_name+'</td>';
                html += '<td>'+comment.income_expenses+'</td>';
                if(comment.memo == null){
                    comment.memo = '无';
                }
                html += '<td>'+comment.memo+'</td>';
                html += '<td><a href="javascript:;" onclick="xiangqing($(this));"  data-toggle="modal" data-target="#myModal" data="'+comment.code+'">查看详情</a></td>';
                html += '</tr>';
             });
        return html;
    }


    //充值信息
    function recharge(data){
        var html = '';

        $.each(data, function(commentIndex, comment){                                               
                html += '<tr><td>'+comment.code+'</td>';
                html += '<td>'+comment.create_time+'</td>';
                html += '<td class="text_green">转入</td>';
                html += '<td>'+comment.pay_type_name+'</td>';
                html += '<td>'+comment.money+'</td>';
                html += '<td>'+comment.status_name+'</td>';
                html += '<td><a href="javascript:;" onclick="xiangqing($(this));"  data-toggle="modal" data-target="#myModal" data="'+comment.code+'">查看详情</a></td>';
                html += '</tr>';
             });

        return html;
    }

    //银宝提现记
    function lurpak_withdrawals(data){
        var html = '';

        $.each(data, function(commentIndex, comment){                                               
                html += '<tr><td>'+comment.code+'</td>';
                html += '<td>'+comment.create_time+'</td>';
                html += '<td class="text_green">转出</td>';
                html += '<td>'+comment.lurpak+'</td>';
                html += '<td>'+comment.status_name+'</td>';
                html += '<td><a href="javascript:;" onclick="xiangqing($(this));"  data-toggle="modal" data-target="#myModal" data="'+comment.code+'">查看详情</a></td>';
                html += '</tr>';
             });

        return html;
    }

    //现金提现记录
    function cash_withdrawals(data){
        var html = '';

        $.each(data, function(commentIndex, comment){                                               
                html += '<tr><td>'+comment.code+'</td>';
                html += '<td>'+comment.create_time+'</td>';
                html += '<td class="text_green">转出</td>';
                html += '<td>'+comment.money+'</td>';
                html += '<td>'+comment.status_name+'</td>';
                html += '<td><a href="javascript:;" onclick="xiangqing($(this));"  data-toggle="modal" data-target="#myModal" data="'+comment.code+'">查看详情</a></td>';
                html += '</tr>';
             });

        return html;
    }

    //购买库存积分记录
    function purchase_integral(data){
        var html = '';

        $.each(data, function(commentIndex, comment){                                               
                html += '<tr><td>'+comment.code+'</td>';
                html += '<td>'+comment.create_time+'</td>';
                html += '<td class="text_green">买入</td>';
                html += '<td>'+comment.money+'</td>';
                html += '<td>'+comment.pay_type_name+'</td>';
                var memo = comment.memo;
                if(!comment.memo){
                    memo = '';
                }
                html += '<td>'+memo+'</td>';
                var status_name = comment.status_name;
                if(comment.status == 1){
                    status_name += '   <a class="btn btn_red mb5" href="/transaction/createForm/code/'+comment.code+'">去付款</a>';
                }
                html += '<td>'+status_name+'</td>';
                html += '<td><a href="javascript:;" onclick="xiangqing($(this));"  data-toggle="modal" data-target="#myModal" data="'+comment.code+'">查看详情</a></td>';
                html += '</tr>';
             });

        return html;
    }

    //积分分发记录（卖家）
    function integral_distribution(data){
        var html = '';

        $.each(data, function(commentIndex, comment){                                               
                html += '<tr><td>'+comment.code+'</td>';
                html += '<td>'+comment.create_time+'</td>';
                html += '<td>'+comment.money+'</td>';
                html += '<td>'+comment.stock+'</td>';
                html += '<td>'+comment.reward_ratio+'</td>';
                html += '<td>'+comment.status_name+'</td>';
                html += '<td>'+comment.memo+'</td>';
                html += '<td><a href="javascript:;" onclick="xiangqing($(this));"  data-toggle="modal" data-target="#myModal" data="'+comment.code+'">查看详情</a></td>';
                html += '</tr>';
             });

        return html;
    }

    //积分分发（买家）
    function stock_integral_distribution(data){
        var html = '';

        $.each(data, function(commentIndex, comment){                                               
                html += '<tr><td>'+comment.code+'</td>';
                html += '<td>'+comment.create_time+'</td>';
                html += '<td>'+comment.money+'</td>';
                html += '<td>'+comment.stock+'</td>';
                html += '<td>'+comment.reward_ratio+'</td>';
                html += '<td class="text_green">已提成</td>';
                html += '<td>'+comment.memo+'</td>';
                html += '<td><a href="javascript:;" onclick="xiangqing($(this));"  data-toggle="modal" data-target="#myModal" data="'+comment.code+'">查看详情</a></td>';
                html += '</tr>';
             });

        return html;
    }

    //线下交易
    function line_transaction(data){
        var html = '';

        $.each(data, function(commentIndex, comment){                                               
                html += '<tr><td>'+comment.code+'</td>';
                html += '<td>'+comment.create_time+'</td>';
                html += '<td>'+comment.money+'</td>';
                html += '<td>'+comment.poundage+'</td>';
                html += '<td>'+comment.status_name+'</td>';
                html += '<td><a href="javascript:;" onclick="xiangqing($(this));"  data-toggle="modal" data-target="#myModal" data="'+comment.code+'">查看详情</a></td>';
                html += '</tr>';
             });

        return html;
    }

    //查看详情
    function xiangqing(obj){
        //获取详情信息
        var code = obj.attr('data');
        var type = document.getElementById('action').value;
        $.ajax({
            type: "POST",
            url: $("#xiangqing").val(),
            data: {code:code},
            dataType: "json",
            //async: true,
            beforeSend: function(){
                $('.spinner').css('display','');
                },
            success: function (data) {
                $('.spinner').css('display','none');
                var html = '';
                if(data.code == 1){
                    //$('.share').empty();   //清空resText里面的所有内容


                         html = details(data,type);
                         $('.text-muted').html(html);


                }else{
                    Lobibox.notify('error',{msg:data.msg});
                }
                return false;
            },
            error: function (msg) {
                Lobibox.notify('error',{msg:'错误！！'});
            }
        });
    }
    //账户异动详情（除现金账户外）
    function details(data,type) {
        if(document.getElementById('action').value == 'Workorder'){
            type = document.getElementById('action').value;
        }
        var html;
        switch(type){
            case 'cash':
                html = account_details(data.data,type);//现金账户记录
                break;
            case 'integral':
                html = account_details(data.data,type);//积分账户记录
                break;
            case 'lurpak':
                html = account_details(data.data,type);//拉伸银宝价值贡献记录
                break;
            case 'commission':
                html = account_details(data.data,type);//提现手续费抵押金记录
                break;
            case 'project':
                html = account_details(data.data,type);//大项目抵押金记录
                break;
            case 'consumption':
                html = account_details(data.data,type);//消费银宝账户记录
                break;
            case 'lurpaks':
                html = account_details(data.data,type);//银宝账户记录
                break;
            case 'stock':
                html = account_details(data.data,type);//库存积分账户记录
                break;
            case 'management':
                html = account_details(data.data,type);//统一管理奖记录
                break;
            case 'index':
                html = recharge_details(data.data,type);//充值记录
                break;
            case 'lurpak_withdrawals':

                html = lurpak_withdrawals_details(data.data);//银宝提现记录
                break;
            case 'cash_withdrawals':

                html = cash_withdrawals_details(data.data);//现金提现记录
                break;

            case 'purchase_integral':
                html = purchase_integral_details(data.data); //购买库存积分记录
                break;
            case 'integral_distribution':
                html = integral_distribution_details(data.data); //积分分记录（买家）
                break;

            case 'stock_integral_distribution':
                html = stock_integral_distribution_details(data.data); //积分分记录（卖家）
                break;
            case 'line_transaction':
                html = line_transaction_details(data.data); //线下交易
                break;
            case 'lurpaktransfer':
                html = lurpaktransfer_details(data.data); //银宝转账
                break;
            case 'vipcommission':
                html = account_details(data.data,type); //vip账户提成
                break;
            case 'Workorder':
                html = Workorder_details(data.data); //工单中心首页
                break;
            case 'adjust_purchase_integral_list':
                html = adjust_purchase_integral_details(data.data); //申请调额详情
                break;
        }
        return html;
    }
    //账户异动详情
    function account_details(data,type) {
        var type_name = '账户数量';
        var type_name2 = '变动数量';
        if(type == 'cash'){
            var type_name = '账户余额';
            var type_name2 = '变动金额';
        }
        var html = ' <div class="form-group mb0">';
        html += '<label class="col-sm-3 pl0 text-right small_xs fs14">编号：</label>';
        html += '<div class="col-sm-8 pl0 line20 fs14">  '+data.code+'  </div>';
        html += '</div>';
        html += '<div class="form-group mb0">';
        html += '<label class="col-sm-3 pl0 text-right small_xs fs14">创建时间：</label>';
        html += '<div class="col-sm-8 pl0 line20 fs14">  '+data.create_time+'   </div>';
        html += '</div>';
        html += '<div class="form-group mb0">';
        html += '<label class="col-sm-3 pl0 text-right small_xs fs14">变动数量：</label>';
        html += '<div class="col-sm-8 pl0 line20 fs14"> '+data.income_expenses+'  </div>';
        html += '</div>';
        html += '<div class="form-group mb0">';
        html += '<label class="col-sm-3 pl0 text-right small_xs fs14">账户数量：</label>';
        html += '<div class="col-sm-8 pl0 line20 fs14"> '+data.balance+'  </div>';
        html += '</div>';
        html += '<div class="form-group mb0">';
        html += '<label class="col-sm-3 pl0 text-right small_xs fs14">交易类型：</label>';
        html += '<div class="col-sm-8 pl0 line20 fs14"> '+data.trade_type_name+'  </div>';
        html += '</div>';
        html += '<div class="form-group mb0">';
        html += '<label class="col-sm-3 pl0 text-right small_xs fs14">流向：</label>';
        html += '<div class="col-sm-8 pl0 line20 fs14"> '+data.flow_direction_name+'  </div>';
        html += '</div>';

        html += '<div class="form-group mb0">';
        html += '<div class="col-sm-2 col-xs-offset-5 pl0 line20 fs14">';
        html += '<div  class="btn btn-block btn-sm btn_blue" data-dismiss="modal">关闭</div>';
        html += '</div>';
        html += '</div>';

        return html;
    }

    //银宝提现记录详情
function lurpak_withdrawals_details(data,type) {
    var html = ' <div class="form-group mb0">';
    html += '<label class="col-sm-3 pl0 text-right small_xs fs14">编号：</label>';
    html += '<div class="col-sm-8 pl0 line20 fs14">  '+data.code+'  </div>';
    html += '</div>';
    html += '<div class="form-group mb0">';
    html += '<label class="col-sm-3 pl0 text-right small_xs fs14">创建时间：</label>';
    html += '<div class="col-sm-8 pl0 line20 fs14">  '+data.create_time+'   </div>';
    html += '</div>';
    html += '<div class="form-group mb0">';
    html += '<label class="col-sm-3 pl0 text-right small_xs fs14">提现云积分：</label>';
    html += '<div class="col-sm-8 pl0 line20 fs14"> '+data.lurpak+'  </div>';
    html += '</div>';
    if(data.use_mortgage_poundage == 1){
        html += '<div class="form-group mb0">';
        html += '<label class="col-sm-3 pl0 text-right small_xs fs14">是否使用抵押金：</label>';
        html += '<div class="col-sm-8 pl0 line20 fs14"> 是  </div>';
        html += '</div>';
    }else{
        html += '<div class="form-group mb0">';
        html += '<label class="col-sm-3 pl0 text-right small_xs fs14">是否使用抵押金：</label>';
        html += '<div class="col-sm-8 pl0 line20 fs14"> 否  </div>';
        html += '</div>';
    }
    html += '<div class="form-group mb0">';
    html += '<label class="col-sm-3 pl0 text-right small_xs fs14">提现手续费：</label>';
    html += '<div class="col-sm-8 pl0 line20 fs14"> '+data.poundage_lurpak+'  </div>';
    html += '</div>';
    html += '<div class="form-group mb0">';
    html += '<label class="col-sm-3 pl0 text-right small_xs fs14">实际到账：</label>';
    html += '<div class="col-sm-8 pl0 line20 fs14"> '+data.arrival_money+'元('+data.arrival_lurpak+')  </div>';
    html += '</div>';
    // html += '<div class="form-group mb0">';
    // html += '<label class="col-sm-3 pl0 text-right small_xs fs14">收款卡号：</label>';
    // html += '<div class="col-sm-8 pl0 line20 fs14"> '+data.bankcard+'-'+data.bank+'  </div>';
    // html += '</div>';
    html += '<div class="form-group mb0">';
    html += '<label class="col-sm-3 pl0 text-right small_xs fs14">预计到账时间：</label>';
    html += '<div class="col-sm-8 pl0 line20 fs14"> '+data.hope_arrival_day+'  </div>';
    html += '</div>';
    html += '<div class="form-group mb0">';
    html += '<label class="col-sm-3 pl0 text-right small_xs fs14">状态：</label>';
    html += '<div class="col-sm-8 pl0 line20 fs14"> '+data.status_name+'  </div>';
    html += '</div>';
    if(data.status == 4){
        html += '<div class="form-group mb0">';
        html += '<label class="col-sm-3 pl0 text-right small_xs fs14">驳回原因：</label>';
        html += '<div class="col-sm-8 pl0 line20 fs14"> '+data.log.reason+'  </div>';
        html += '</div>';
        html += '<div class="form-group mb0">';
        html += '<label class="col-sm-3 pl0 text-right small_xs fs14">驳回时间：</label>';
        html += '<div class="col-sm-8 pl0 line20 fs14"> '+data.log.create_time+'  </div>';
        html += '</div>';
    }
    html += '<div class="form-group mb0">';
    html += '<div class="col-sm-2 col-xs-offset-5 pl0 line20 fs14">';
    html += '<div  class="btn btn-block btn-sm btn_blue" data-dismiss="modal">关闭</div>';
    html += '</div>';
    html += '</div>';

    return html;
}

//现金提现记录详情
function cash_withdrawals_details(data,type) {
    var html = ' <div class="form-group mb0">';
    html += '<label class="col-sm-3 pl0 text-right small_xs fs14">编号：</label>';
    html += '<div class="col-sm-8 pl0 line20 fs14">  '+data.code+'  </div>';
    html += '</div>';
    html += '<div class="form-group mb0">';
    html += '<label class="col-sm-3 pl0 text-right small_xs fs14">创建时间：</label>';
    html += '<div class="col-sm-8 pl0 line20 fs14">  '+data.create_time+'   </div>';
    html += '</div>';
    html += '<div class="form-group mb0">';
    html += '<label class="col-sm-3 pl0 text-right small_xs fs14">提现金额：</label>';
    html += '<div class="col-sm-8 pl0 line20 fs14"> '+data.money+'  </div>';
    html += '</div>';
    if(data.use_mortgage_poundage == 1){
        html += '<div class="form-group mb0">';
        html += '<label class="col-sm-3 pl0 text-right small_xs fs14">是否使用抵押金：</label>';
        html += '<div class="col-sm-8 pl0 line20 fs14"> 是  </div>';
        html += '</div>';
        html += '<div class="form-group mb0">';
        html += '<label class="col-sm-3 pl0 text-right small_xs fs14">提现手续费：</label>';
        html += '<div class="col-sm-8 pl0 line20 fs14"> '+data.poundage+'元</div>';
        html += '</div>';
    }else{
        html += '<div class="form-group mb0">';
        html += '<label class="col-sm-3 pl0 text-right small_xs fs14">是否使用抵押金：</label>';
        html += '<div class="col-sm-8 pl0 line20 fs14"> 否  </div>';
        html += '</div>';
        html += '<div class="form-group mb0">';
        html += '<label class="col-sm-3 pl0 text-right small_xs fs14">提现手续费：</label>';
        html += '<div class="col-sm-8 pl0 line20 fs14"> '+data.pay_poundage+'元</div>';
        html += '</div>';
    }

    html += '<div class="form-group mb0">';
    html += '<label class="col-sm-3 pl0 text-right small_xs fs14">实际到账：</label>';
    html += '<div class="col-sm-8 pl0 line20 fs14"> '+data.arrival_money+'元 </div>';
    html += '</div>';
    html += '<div class="form-group mb0">';
    html += '<label class="col-sm-3 pl0 text-right small_xs fs14">收款卡号：</label>';
    html += '<div class="col-sm-8 pl0 line20 fs14"> '+data.bankcard+'-'+data.bank+'  </div>';
    html += '</div>';
    html += '<div class="form-group mb0">';
    html += '<label class="col-sm-3 pl0 text-right small_xs fs14">预计到账时间：</label>';
    html += '<div class="col-sm-8 pl0 line20 fs14"> '+data.hope_arrival_day+'  </div>';
    html += '</div>';
    html += '<div class="form-group mb0">';
    html += '<label class="col-sm-3 pl0 text-right small_xs fs14">状态：</label>';
    html += '<div class="col-sm-8 pl0 line20 fs14"> '+data.status_name+'  </div>';
    html += '</div>';
    if(data.status == 4){
        html += '<div class="form-group mb0">';
        html += '<label class="col-sm-3 pl0 text-right small_xs fs14">驳回原因：</label>';
        html += '<div class="col-sm-8 pl0 line20 fs14"> '+data.log.reason+'  </div>';
        html += '</div>';
        html += '<div class="form-group mb0">';
        html += '<label class="col-sm-3 pl0 text-right small_xs fs14">驳回时间：</label>';
        html += '<div class="col-sm-8 pl0 line20 fs14"> '+data.log.create_time+'  </div>';
        html += '</div>';
    }
    html += '<div class="form-group mb0">';
    html += '<div class="col-sm-2 col-xs-offset-5 pl0 line20 fs14">';
    html += '<div  class="btn btn-block btn-sm btn_blue" data-dismiss="modal">关闭</div>';
    html += '</div>';
    html += '</div>';

    return html;
}

//充值记录详情
function recharge_details(data,type) {
    var html = ' <div class="form-group mb0">';
    html += '<label class="col-sm-3 pl0 text-right small_xs fs14">编号：</label>';
    html += '<div class="col-sm-8 pl0 line20 fs14">  '+data.code+'  </div>';
    html += '</div>';
    html += '<div class="form-group mb0">';
    html += '<label class="col-sm-3 pl0 text-right small_xs fs14">创建时间：</label>';
    html += '<div class="col-sm-8 pl0 line20 fs14">  '+data.create_time+'   </div>';
    html += '</div>';
    html += '<div class="form-group mb0">';
    html += '<label class="col-sm-3 pl0 text-right small_xs fs14">充值金额：</label>';
    html += '<div class="col-sm-8 pl0 line20 fs14"> '+data.money+'  </div>';
    html += '</div>';
    html += '<div class="form-group mb0">';
    html += '<label class="col-sm-3 pl0 text-right small_xs fs14">支付时间：</label>';
    html += '<div class="col-sm-8 pl0 line20 fs14"> '+data.pay_time+'  </div>';
    html += '</div>';
    html += '<div class="form-group mb0">';
    html += '<label class="col-sm-3 pl0 text-right small_xs fs14">支付类型：</label>';
    html += '<div class="col-sm-8 pl0 line20 fs14"> '+data.pay_type_name+' </div>';
    html += '</div>';
    // html += '<div class="form-group mb0">';
    // html += '<label class="col-sm-3 pl0 text-right small_xs fs14">实际到账金额：</label>';
    // html += '<div class="col-sm-8 pl0 line20 fs14"> '+data.poundage+'  </div>';
    // html += '</div>';
    html += '<div class="form-group mb0">';
    html += '<label class="col-sm-3 pl0 text-right small_xs fs14">状态：</label>';
    html += '<div class="col-sm-8 pl0 line20 fs14"> '+data.status_name+'  </div>';
    html += '</div>';
    html += '<div class="form-group mb0">';
    html += '<div class="col-sm-2 col-xs-offset-5 pl0 line20 fs14">';
    html += '<div  class="btn btn-block btn-sm btn_blue" data-dismiss="modal">关闭</div>';
    html += '</div>';
    html += '</div>';

    return html;
}

//购买库存积分详情
function purchase_integral_details(data,type) {
        var pay_type_name = '金额';
        var money = data.money;
        if(data.pay_type == 2){
            pay_type_name = '云积分';
            money = data.lurpak;
        }
    var html = ' <div class="form-group mb0">';
    html += '<label class="col-sm-3 pl0 text-right small_xs fs14">编号：</label>';
    html += '<div class="col-sm-8 pl0 line20 fs14">  '+data.code+'  </div>';
    html += '</div>';
    html += '<div class="form-group mb0">';
    html += '<label class="col-sm-3 pl0 text-right small_xs fs14">创建时间：</label>';
    html += '<div class="col-sm-8 pl0 line20 fs14">  '+data.create_time+'   </div>';
    html += '</div>';
    html += '<div class="form-group mb0">';
    html += '<label class="col-sm-3 pl0 text-right small_xs fs14">购买金额：</label>';
    html += '<div class="col-sm-8 pl0 line20 fs14"> '+data.money+'  </div>';
    html += '</div>';
    html += '<div class="form-group mb0">';
    html += '<label class="col-sm-3 pl0 text-right small_xs fs14">支付'+pay_type_name+'：</label>';
    html += '<div class="col-sm-8 pl0 line20 fs14"> '+money+'  </div>';
    html += '</div>';
    html += '<div class="form-group mb0">';
    html += '<label class="col-sm-3 pl0 text-right small_xs fs14">资金流向：</label>';
    html += '<div class="col-sm-8 pl0 line20 fs14"> 库存积分买入 </div>';
    html += '</div>';
    html += '<div class="form-group mb0">';
    html += '<label class="col-sm-3 pl0 text-right small_xs fs14">库存积分：</label>';
    html += '<div class="col-sm-8 pl0 line20 fs14"> '+data.stock+'  </div>';
    html += '</div>';
    html += '<div class="form-group mb0">';
    html += '<label class="col-sm-3 pl0 text-right small_xs fs14">支付类型：</label>';
    html += '<div class="col-sm-8 pl0 line20 fs14"> '+data.pay_type_name+'  </div>';
    html += '</div>';
    // html += '<div class="form-group mb0">';
    // html += '<label class="col-sm-3 pl0 text-right small_xs fs14">状态：</label>';
    // html += '<div class="col-sm-8 pl0 line20 fs14"> '+data.status_name+'  </div>';
    // html += '</div>';
    html += '<div class="form-group mb0">';
    html += '<div class="col-sm-2 col-xs-offset-5 pl0 line20 fs14">';
    html += '<div  class="btn btn-block btn-sm btn_blue" data-dismiss="modal">关闭</div>';
    html += '</div>';
    html += '</div>';

    return html;
}

//积分分发详情
function integral_distribution_details(data,type) {
    var html = ' <div class="form-group mb0">';
    html += '<label class="col-sm-3 pl0 text-right small_xs fs14">编号：</label>';
    html += '<div class="col-sm-8 pl0 line20 fs14">  '+data.code+'  </div>';
    html += '</div>';
    html += '<div class="form-group mb0">';
    html += '<label class="col-sm-3 pl0 text-right small_xs fs14">创建时间：</label>';
    html += '<div class="col-sm-8 pl0 line20 fs14">  '+data.create_time+'   </div>';
    html += '</div>';
    html += '<div class="form-group mb0">';
    html += '<label class="col-sm-3 pl0 text-right small_xs fs14">金额：</label>';
    html += '<div class="col-sm-8 pl0 line20 fs14"> '+data.money+'  </div>';
    html += '</div>';
    html += '<div class="form-group mb0">';
    html += '<label class="col-sm-3 pl0 text-right small_xs fs14">倍数：</label>';
    html += '<div class="col-sm-8 pl0 line20 fs14"> '+data.reward_ratio+'  </div>';
    html += '</div>';
    html += '<div class="form-group mb0">';
    html += '<label class="col-sm-3 pl0 text-right small_xs fs14">库存积分：</label>';
    html += '<div class="col-sm-8 pl0 line20 fs14"> '+data.stock+' </div>';
    html += '</div>';
    html += '<div class="form-group mb0">';
    html += '<label class="col-sm-3 pl0 text-right small_xs fs14">用户账号：</label>';
    html += '<div class="col-sm-8 pl0 line20 fs14"> '+data.buyer.account+'  </div>';
    html += '</div>';
    html += '<div class="form-group mb0">';
    html += '<label class="col-sm-3 pl0 text-right small_xs fs14">用户手机：</label>';
    html += '<div class="col-sm-8 pl0 line20 fs14"> '+data.buyer.mobile+'  </div>';
    html += '</div>';
    html += '<div class="form-group mb0">';
    html += '<label class="col-sm-3 pl0 text-right small_xs fs14">备注：</label>';
    html += '<div class="col-sm-8 pl0 line20 fs14"> '+data.memo+'  </div>';
    html += '</div>';
    html += '<div class="form-group mb0">';
    html += '<label class="col-sm-3 pl0 text-right small_xs fs14">状态：</label>';
    html += '<div class="col-sm-8 pl0 line20 fs14"> '+data.status_name+'  </div>';
    html += '</div>';
    if(data.status == 3){
        html += '<div class="form-group mb0">';
        html += '<label class="col-sm-3 pl0 text-right small_xs fs14">驳回原因：</label>';
        html += '<div class="col-sm-8 pl0 line20 fs14"> '+data.logs.reason+'  </div>';
        html += '</div>';
    }
    html += '<div class="form-group mb0">';
    html += '<div class="col-sm-2 col-xs-offset-5 pl0 line20 fs14">';
    html += '<div  class="btn btn-block btn-sm btn_blue" data-dismiss="modal">关闭</div>';
    html += '</div>';
    html += '</div>';

    return html;
}

//线下交易详情
function line_transaction_details(data,type) {

    var html = ' <div class="form-group mb0">';
    html += '<label class="col-sm-3 pl0 text-right small_xs fs14">编号：</label>';
    html += '<div class="col-sm-8 pl0 line20 fs14">  '+data.code+'  </div>';
    html += '</div>';
    html += '<div class="form-group mb0">';
    html += '<label class="col-sm-3 pl0 text-right small_xs fs14">创建时间：</label>';
    html += '<div class="col-sm-8 pl0 line20 fs14">  '+data.create_time+'   </div>';
    html += '</div>';
    html += '<div class="form-group mb0">';
    html += '<label class="col-sm-3 pl0 text-right small_xs fs14">收款人姓名：</label>';
    html += '<div class="col-sm-8 pl0 line20 fs14"> '+data.name+'  </div>';
    html += '</div>';
    html += '<div class="form-group mb0">';
    html += '<label class="col-sm-3 pl0 text-right small_xs fs14">收款银行：</label>';
    html += '<div class="col-sm-8 pl0 line20 fs14"> '+data.bank.name+'  </div>';
    html += '</div>';
    html += '<div class="form-group mb0">';
    html += '<label class="col-sm-3 pl0 text-right small_xs fs14">收款卡号：</label>';
    html += '<div class="col-sm-8 pl0 line20 fs14"> '+data.bankcard+' </div>';
    html += '</div>';
    html += '<div class="form-group mb0">';
    html += '<label class="col-sm-3 pl0 text-right small_xs fs14">转账金额：</label>';
    html += '<div class="col-sm-8 pl0 line20 fs14"> '+data.money+'  </div>';
    html += '</div>';
    html += '<div class="form-group mb0">';
    html += '<label class="col-sm-3 pl0 text-right small_xs fs14">实际到账金额：</label>';
    html += '<div class="col-sm-8 pl0 line20 fs14"> '+data.arrival_moeny+'  </div>';
    html += '</div>';
    html += '<div class="form-group mb0">';
    html += '<label class="col-sm-3 pl0 text-right small_xs fs14">手续费用：</label>';
    html += '<div class="col-sm-8 pl0 line20 fs14"> '+data.poundage+'  </div>';
    html += '</div>';
    html += '<div class="form-group mb0">';
    html += '<label class="col-sm-3 pl0 text-right small_xs fs14">转账凭据：</label>';
    html += '<div class="col-sm-8 pl0 line20 fs14"> <a href="'+data.voucher_pic+'" target="_blank" id="voucher_pic"><img width="90px;" src="'+data.voucher_pic+'"/></a>  </div>';
    html += '</div>';
    html += '<div class="form-group mb0">';
    html += '<label class="col-sm-3 pl0 text-right small_xs fs14">到账时间：</label>';
    html += '<div class="col-sm-8 pl0 line20 fs14"> '+data.confirm_date+'  </div>';
    html += '</div>';

    html += '<div class="form-group mb0">';
    html += '<label class="col-sm-3 pl0 text-right small_xs fs14">状态：</label>';
    html += '<div class="col-sm-8 pl0 line20 fs14"> '+data.status_name+'  </div>';
    html += '</div>';
    if(data.log.reason && data.log.create_time){
        html += '<div class="form-group mb0">';
        html += '<label class="col-sm-3 pl0 text-right small_xs fs14">驳回时间：</label>';
        html += '<div class="col-sm-8 pl0 line20 fs14"> '+data.log.create_time+'  </div>';
        html += '</div>';


        html += '<div class="form-group mb0">';
        html += '<label class="col-sm-3 pl0 text-right small_xs fs14">驳回原因：</label>';
        html += '<div class="col-sm-8 pl0 line20 fs14"> '+data.log.reason+'  </div>';
        html += '</div>';
    }
    html += '<div class="form-group mb0">';
    html += '<div class="col-sm-2 col-xs-offset-5 pl0 line20 fs14">';
    html += '<div  class="btn btn-block btn-sm btn_blue" data-dismiss="modal">关闭</div>';
    html += '</div>';
    html += '</div>';

    return html;
}

//银宝转账详情
function lurpaktransfer_details(data,type) {
    var html = ' <div class="form-group mb0">';
    html += '<label class="col-sm-3 pl0 text-right small_xs fs14">编号：</label>';
    html += '<div class="col-sm-8 pl0 line20 fs14">  '+data.code+'  </div>';
    html += '</div>';
    html += '<div class="form-group mb0">';
    html += '<label class="col-sm-3 pl0 text-right small_xs fs14">创建时间：</label>';
    html += '<div class="col-sm-8 pl0 line20 fs14">  '+data.create_time+'   </div>';
    html += '</div>';
    html += '<div class="form-group mb0">';
    html += '<label class="col-sm-3 pl0 text-right small_xs fs14">转账云积分：</label>';
    html += '<div class="col-sm-8 pl0 line20 fs14"> '+data.lurpak+'  </div>';
    html += '</div>';
    html += '<div class="form-group mb0">';
    html += '<label class="col-sm-3 pl0 text-right small_xs fs14">实际到账：</label>';
    html += '<div class="col-sm-8 pl0 line20 fs14"> '+data.arrival_lurpak+'  </div>';
    html += '</div>';
    html += '<div class="form-group mb0">';
    html += '<label class="col-sm-3 pl0 text-right small_xs fs14">手续费：</label>';
    html += '<div class="col-sm-8 pl0 line20 fs14"> '+data.pay_poundage_lurpak+' </div>';
    html += '</div>';
    html += '<div class="form-group mb0">';
    html += '<label class="col-sm-3 pl0 text-right small_xs fs14">从转账数量中扣除的手续费：</label>';
    html += '<div class="col-sm-8 pl0 line20 fs14"> '+data.lurpak_dec+'  </div>';
    html += '</div>';
    html += '<div class="form-group mb0">';
    html += '<label class="col-sm-3 pl0 text-right small_xs fs14">抵押金扣除数量：</label>';
    html += '<div class="col-sm-8 pl0 line20 fs14"> '+data.mortgage_poundage_dec+'  </div>';
    html += '</div>';
    html += '<div class="form-group mb0">';
    html += '<label class="col-sm-3 pl0 text-right small_xs fs14">手续费率：</label>';
    html += '<div class="col-sm-8 pl0 line20 fs14"> '+data.poundage_ratio * 100+'%  </div>';
    html += '</div>';
    html += '<div class="form-group mb0">';
    html += '<label class="col-sm-3 pl0 text-right small_xs fs14">收款人账号：</label>';
    html += '<div class="col-sm-8 pl0 line20 fs14"> '+data.to_user.account+'  </div>';
    html += '</div>';
    html += '<div class="form-group mb0">';
    html += '<label class="col-sm-3 pl0 text-right small_xs fs14">收款人手机：</label>';
    html += '<div class="col-sm-8 pl0 line20 fs14"> '+data.to_user.mobile+'  </div>';
    html += '</div>';
    html += '<div class="form-group mb0">';
    html += '<div class="col-sm-2 col-xs-offset-5 pl0 line20 fs14">';
    html += '<div  class="btn btn-block btn-sm btn_blue" data-dismiss="modal">关闭</div>';
    html += '</div>';
    html += '</div>';

    return html;
}

//积分分发详情
function Workorder_details(data,type) {
    var html = ' <div class="form-group mb0">';
    html += '<label class="col-sm-3 pl0 text-right small_xs fs14">工单编号：</label>';
    html += '<div class="col-sm-8 pl0 line20 fs14">  '+data.id+'  </div>';
    html += '</div>';
    html += '<div class="form-group mb0">';
    html += '<label class="col-sm-3 pl0 text-right small_xs fs14">工单类型：</label>';
    html += '<div class="col-sm-8 pl0 line20 fs14">  '+data.typeName+'   </div>';
    html += '</div>';
    html += '<div class="form-group mb0">';
    html += '<label class="col-sm-3 pl0 text-right small_xs fs14">提交时间：</label>';
    html += '<div class="col-sm-8 pl0 line20 fs14"> '+data.create_time+'  </div>';
    html += '</div>';
    html += '<div class="form-group mb0">';
    html += '<label class="col-sm-3 pl0 text-right small_xs fs14">状态：</label>';
    html += '<div class="col-sm-8 pl0 line20 fs14"> '+data.dealType+'  </div>';
    html += '</div>';
    html += '<div class="form-group mb0">';
    html += '<label class="col-sm-3 pl0 text-right small_xs fs14">原值：</label>';
    html += '<div class="col-sm-8 pl0 line20 fs14"> '+data.old_value+' </div>';
    html += '</div>';
    html += '<div class="form-group mb0">';
    html += '<label class="col-sm-3 pl0 text-right small_xs fs14">修改值：</label>';
    html += '<div class="col-sm-8 pl0 line20 fs14"> '+data.new_value+'  </div>';
    html += '</div>';
    html += '<div class="form-group mb0">';
    html += '<label class="col-sm-3 pl0 text-right small_xs fs14">身份证正面：</label>';
    html += '<div class="col-sm-8 pl0 line20 fs14"> <a href="'+data.photo0+'" target="_blank" id="voucher_pic"><img width="90px;" src="'+data.photo0+'"/></a>  </div>';
    html += '</div>';
    html += '<div class="form-group mb0">';
    html += '<label class="col-sm-3 pl0 text-right small_xs fs14">身份证反面：</label>';
    html += '<div class="col-sm-8 pl0 line20 fs14"> <a href="'+data.photo1+'" target="_blank" id="voucher_pic"><img width="90px;" src="'+data.photo1+'"/></a>  </div>';
    html += '</div>';
    html += '<div class="form-group mb0">';
    html += '<div class="col-sm-2 col-xs-offset-5 pl0 line20 fs14">';
    html += '<div  class="btn btn-block btn-sm btn_blue" data-dismiss="modal">关闭</div>';
    html += '</div>';
    html += '</div>';

    return html;
}

//工单详情
function stock_integral_distribution_details(data,type) {

    var html = ' <div class="form-group mb0">';
    html += '<label class="col-sm-3 pl0 text-right small_xs fs14">编号：</label>';
    html += '<div class="col-sm-8 pl0 line20 fs14">  '+data.code+'  </div>';
    html += '</div>';
    html += '<div class="form-group mb0">';
    html += '<label class="col-sm-3 pl0 text-right small_xs fs14">创建时间：</label>';
    html += '<div class="col-sm-8 pl0 line20 fs14">  '+data.create_time+'   </div>';
    html += '</div>';
    html += '<div class="form-group mb0">';
    html += '<label class="col-sm-3 pl0 text-right small_xs fs14">订单金额：</label>';
    html += '<div class="col-sm-8 pl0 line20 fs14"> '+data.money+'  </div>';
    html += '</div>';
    html += '<div class="form-group mb0">';
    html += '<label class="col-sm-3 pl0 text-right small_xs fs14">获得积分：</label>';
    html += '<div class="col-sm-8 pl0 line20 fs14"> '+data.stock+'  </div>';
    html += '</div>';
    html += '<div class="form-group mb0">';
    html += '<label class="col-sm-3 pl0 text-right small_xs fs14">倍数：</label>';
    html += '<div class="col-sm-8 pl0 line20 fs14"> '+data.reward_ratio+' </div>';
    html += '</div>';
    html += '<div class="form-group mb0">';
    html += '<label class="col-sm-3 pl0 text-right small_xs fs14">用户名：</label>';
    html += '<div class="col-sm-8 pl0 line20 fs14"> '+data.seller.account+'  </div>';
    html += '</div>';
    html += '<div class="form-group mb0">';
    html += '<label class="col-sm-3 pl0 text-right small_xs fs14">用户手机：</label>';
    html += '<div class="col-sm-8 pl0 line20 fs14"> '+data.seller.mobile+'  </div>';
    html += '</div>';
    html += '<div class="form-group mb0">';
    html += '<label class="col-sm-3 pl0 text-right small_xs fs14">备注：</label>';
    html += '<div class="col-sm-8 pl0 line20 fs14"> '+data.memo+'  </div>';
    html += '</div>';
    html += '<div class="form-group mb0">';
    html += '<div class="col-sm-2 col-xs-offset-5 pl0 line20 fs14">';
    html += '<div  class="btn btn-block btn-sm btn_blue" data-dismiss="modal">关闭</div>';
    html += '</div>';
    html += '</div>';

    return html;
}

function adjust_purchase_integral_details(data,type) {
    //处理图片
    var img = '';
    for (var i=0; i< data.picture.length; i++){
        img += '<a href="'+data.picture[i]+'" style="margin-right:10px;" target="_blank">查看</a>';
    }
    var html = ' <div class="form-group mb0">';
    html += '<label class="col-sm-3 pl0 text-right small_xs fs14">编号：</label>';
    html += '<div class="col-sm-8 pl0 line20 fs14">  '+data.code+'  </div>';
    html += '</div>';
    html += '<div class="form-group mb0">';
    html += '<label class="col-sm-3 pl0 text-right small_xs fs14">处理时间：</label>';
    html += '<div class="col-sm-8 pl0 line20 fs14">  '+data.confirm_date+'   </div>';
    html += '</div>';
    html += '<div class="form-group mb0">';
    html += '<label class="col-sm-3 pl0 text-right small_xs fs14">单笔限额：</label>';
    html += '<div class="col-sm-8 pl0 line20 fs14"> '+data.slimit+'  </div>';
    html += '</div>';
    html += '<div class="form-group mb0">';
    html += '<label class="col-sm-3 pl0 text-right small_xs fs14">月限额：</label>';
    html += '<div class="col-sm-8 pl0 line20 fs14"> '+data.dlimit+'  </div>';
    html += '</div>';
    html += '<div class="form-group mb0">';
    html += '<label class="col-sm-3 pl0 text-right small_xs fs14">申请类型：</label>';
    html += '<div class="col-sm-8 pl0 line20 fs14"> '+data.type_name+' </div>';
    html += '</div>';
    html += '<div class="form-group mb0">';
    html += '<label class="col-sm-3 pl0 text-right small_xs fs14">状态：</label>';
    html += '<div class="col-sm-8 pl0 line20 fs14"> '+data.status_name+'  </div>';
    html += '</div>';
    html += '<div class="form-group mb0">';
    html += '<label class="col-sm-3 pl0 text-right small_xs fs14">申请凭证：</label>';
    html += '<div class="col-sm-8 pl0 line20 fs14"> '+img+'  </div>';
    html += '</div>';
    html += '<div class="form-group mb0">';
    html += '<label class="col-sm-3 pl0 text-right small_xs fs14">申请原因：</label>';
    html += '<div class="col-sm-8 pl0 line20 fs14"> '+data.reason+'  </div>';
    html += '</div>';
    html += '<div class="form-group mb0">';
    html += '<div class="col-sm-2 col-xs-offset-5 pl0 line20 fs14">';
    html += '<div  class="btn btn-block btn-sm btn_blue" data-dismiss="modal">关闭</div>';
    html += '</div>';
    html += '</div>';

    return html;
}

