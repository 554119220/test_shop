/**
 * Created by Administrator on 2017/11/20 0020.
 */
var is_inline_page = false;
var vm = [];
$(function () {
    'use strict';

    $(document).on('pageAnimationStart', '.page', function (e, id, page) {
        is_inline_page = $(".page-group").find('#' + id).data('load');
        is_inline_page = is_inline_page !== false && is_inline_page !== undefined && is_inline_page !== '' ? true : false;
        // if (id === 'page_goods_index') console.log("pageAnimationStart", $('.content').scrollTop())
        if (is_inline_page) {
            window.location.reload()
        }
    });

    $(document).on('beforePageSwitch', '.page', function (e, id, page) {
        // if (id === 'page_goods_index') console.log("beforePageSwitch", $('.content').scrollTop())
        if (is_inline_page === false) $('.content').scrollTop( 0 );
    });

    $(document).on('pageInit', '.page', function (e, id, page) {
        var content = $("#" + id);
        content.find('.btn-submit').click(function () {
            var $this = $(this);
            if ($this.hasClass('btn-disabled')) {
                return;
            } else {
                $this.addClass('btn-disabled')
            }
        });
        $("#" + id).attr('data-load', 'true');
        $.hidePreloader();
        $.closeModal();
        $.closePanel();
        // $('.back').attr('data-no-cache', 'true');
        //  上拉刷新页面
        // $(".back").click(function () {
        //     history.go(-1);
        // });
        // $(".swiper-container").swiper();  //banner
        $(document).on('refresh', '.content', function (e) {
            window.location.reload()
        });

        //返回顶部
        var bSys=true;
        var timer=null;
        $('.content').scroll(function(e){
            if( $(this).scrollTop() > 100){
                $('.go_top').show()
            }else{
                $('.go_top').hide()
            }
            if(!bSys){
                clearInterval(timer);
            }
            bSys=false;
        })

        $('.go_top').click(function(){
            timer=setInterval(function (){
                var iSpeed = Math.floor(-$('.content').scrollTop()/4);
                if($('.content').scrollTop()==0){
                    clearInterval(timer);
                }
                bSys=true;
                $('.content').scrollTop( $('.content').scrollTop()+iSpeed );
            }, 30);
        })

    });

    /**
     * 首页脚本
     *
     *  page_控制器名称_方法名称
     */
    $(document).on("pageInit", "#page_index_index", function (e, id, page) {
        var content= $("#" + id);
        $(function(){ //轮播图
            var swiper = new Swiper('.swiper-index-banner', {
				autoplay: { //自动播放6秒
					delay: 4000,
                    stopOnLastSlide: false,
                    disableOnInteraction: false,
				},
                spaceBetween: 0,
                centeredSlides: true,
                loop : true,
                // spaceBetween: 10,
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
            });
        });
        $(function(){  //轮播切换
            var swiper = new Swiper('.swiper-index—nav', {
                slidesPerView: 1,
                spaceBetween: 10,
                loop : true,
                //freeMode: true,
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
            });
        });

        // $(".swiper-index-banner").swiper();

        // $('.content').scroll(function(e){
        //     var i = $('.content').scrollTop()/100;
        //     $('header.bar.bar-nav').css({'background-color':'rgba(216,33,40,'+i+')'})
        // });

        //首页改版
        $(function(){
            $(document).on("click", ".set_fles >a", function (e) { //频道页面选择
                $(this).addClass('active').siblings().removeClass('active')
                $(this).parents('.set_fles').find('.fix').hide()
                $(this).parents('.set_fles').find('td').removeClass('active')
                $(this).parents('.set_fles').find('td').eq($(this).index()).addClass('active')
            })
            $(document).on("click", ".set_fles .fix span, .mask_page.modal-overlay-visible", function (e) { //关闭遮罩 关闭弹层
                $('.set_fles .fix').hide()
                $('.mask_page').removeClass('modal-overlay-visible')
            })

            $(document).on("click", ".set_fles >span", function (e) { //打开遮罩 打开弹层
                $(this).siblings('.fix').show()
                $('.mask_page').addClass('modal-overlay-visible')
            })

            $(document).on("click", ".set_fles td", function (e) { //频道页面弹层内容选择
                if($(this).text()){
                    if($('.set_fles td').index(this) > Number($('.set_fles >a').size()-1) ){
                        $('.set_fles a').removeClass('active')
                    }else{
                        $('.set_fles a').eq($('.set_fles td').index(this)).addClass('active').siblings().removeClass('active')
                    }
                    $(this).parents('.fix').find('td').removeClass('active')
                    $(this).addClass('active')
                    $(this).parents('.fix').hide()
                    $('.mask_page').removeClass('modal-overlay-visible')
                }
            })
        })

        content.find("#search").click(function () {
            $.router.load('/search');
        });
        var fields  = [
            '',
            'goods_sale_num',
            'goods_attention_num',
            'goods_comment_num',
            'goods_comment_good_num',
            'goods_score_multi',
            'goods_min_price',
            // 'goods_create_time',
            'goods_shopping_score_multi',
            'goods_score_multi',
            // 'goods_update_time',
            'goods_sku_num',
            'goods_service_days',
        ];
        var sort    = [
            'asc',
            'desc'
        ];
        var key = Math.ceil(Math.random() * 10);
        var vm  = vms('goods-list1', '#goods-list1', "#" + id + " #index-goods-list", '/index/like', 'get');
        vm.request({page : 1, order : fields[key], sort : sort[key % 2]});
        //  上拉加载
        var loading = false;
        // 注册'infinite'事件处理函数
        $(document).find(content).on('infinite', '.infinite-scroll-bottom',function() {
            var request     = content.find('#index-goods-list').data('request');
            var page        = parseInt(content.find('#index-goods-list').data('page'));
            if (loading || request === 2) return;
            loading = true;
            var data    = {page : page, order : fields[key], sort : sort[key % 2]};
            vm.request(data);
            loading = false;
            //容器发生改变,如果是js滚动，需要刷新滚动
            $.refreshScroller();
        });

    });

    /**
     * 购物车首页
     */
    $(document).on("pageInit", "#page_cart_index", function (e, id, page) {
        var content = $("#" + id);
        $(".btn-plus-num").click(function () {
            var header  = JSON.parse(content.data('headers_plus'));
            var cart_id = $(this).closest('li').data('id');
            var data    = {cart_id : cart_id};
            var prev    = $(this).prev();
            var num     = parseInt(prev.html());
            ++num;
            if (num === 999) {
                $.toast("不能大于999件");
                return;
            }
            numberSubmit(data, header, prev, num);
        });

        $(".btn-less-num").click(function () {
            var header  = JSON.parse(content.data('headers_less'));
            var cart_id = $(this).closest('li').data('id');
            var data    = {cart_id : cart_id};
            var next    = $(this).next();
            var num     = parseInt(next.html());
            --num;
            if (num === 0) {
                $.toast("不能小于1件");
                return;
            }
            // next.html(num);
            numberSubmit(data, header, next, num);
        });

        $(".btn-delete-goods").click(function () {
            var header  = JSON.parse(content.data('headers_delete'));
            var cart_id = $(this).closest('li').data('id');
            var data    = {cart_ids : cart_id};
            var $this   = $(this);
            $.confirm('您真的要删除当前商品吗？', function () {
                $.showPreloader();
                var params  = {
                    header : header,
                    data : data,
                    success : function (ret) {
                        $.hidePreloader();
                        $.toast(ret.msg);
                        var goods_num   = $this.closest('ul').find('li').size();
                        if (goods_num <= 1) {
                            $this.closest('div.shop-box').remove();
                        }
                        $this.closest('li').remove();
                    }
                };
                apiPost(params);
            });
        });
        /**
         * 购物车数量修改
         *
         * @param data
         * @param header
         */
        var numberSubmit    = function (data, header, that, num) {
            var params = {
                header : header,
                data : data,
                success : function (ret) {
                    if (ret.code !== 20000) {
                        $.toast(ret.msg);
                    } else {
                        that.closest('li').find('.goods-num').html(num);
                        var single_price    = Number(that.closest('li').find('.goods-single-price').data('price'));
                        var total_price     = num * single_price;
                        that.closest('li').find('.goods-single-price').html(total_price.toFixed(2));
                        that.html(num);
                        calcChooseNum();
                    }
                }
            };
            apiPost(params);
        };

        //  单选
        $(".btn-choose-goods").click(function () {
            if ($(this).hasClass('dui_')) {
                $(this).removeClass('dui_');
            } else {
                $(this).addClass('dui_');
            }
            calcChooseNum();
        });

        //  店铺全选
        $(".btn-choose-shop").click(function () {
            var goods = $(this).parent().next().find('.btn-choose-goods');
            if ($(this).hasClass('dui_')) {
                goods.removeClass('dui_');
                $(this).removeClass('dui_');
            } else {
                goods.addClass('dui_');
                $(this).addClass('dui_');
            }
            calcChooseNum();
        });

        //  全选
        $(".btn-choose-all").click(function () {
            var dui = content.find('.btn-choose-goods');
            var sdui = content.find('.btn-choose-shop');
            if ($(this).hasClass('dui_')) {
                $(this).removeClass('dui_');
                dui.removeClass('dui_');
                sdui.removeClass('dui_');
            } else {
                $(this).addClass('dui_');
                dui.addClass('dui_');
                sdui.addClass('dui_');
            }
            calcChooseNum();
        });

        //box-count-choose-amount

        //  统计勾选数量
        var calcChooseNum   = function () {
            var choose  = $(".btn-choose-goods.dui_");
            var num = choose.size();
            $(".box-choose-num").html(num);
            var amount  = 0.00;
            var cart_ids= '';
            $.each(choose, function (index, item) {
                var $this = $(item);
                var goods_single_price  = Number($this.closest('li').find('.goods-single-price').html());
                //var goods_num           = Number($this.closest('li').find('.goods-num').html());
                amount  += goods_single_price;
                cart_ids+= $this.closest('li').data('id') + ',';
            });
            content.find("input[name='cart_ids']").val(cart_ids);
            content.find('.box-count-choose-amount').html(amount.toFixed(2));
        };
        //  提交表单
        content.find('.btn-submit').click(function () {
            var ids = content.find("input[name='cart_ids']").val();
            if (ids == undefined || ids == '') {
                $.toast('请选择商品');
                return;
            }
            $.showPreloader('数据提交中...');
            content.find('form').submit();
        });


        //  批量修改
        content.find('.btn-batch-edit').click(function() {
            var $this = $(this);
            var tools_box   = content.find('.batch-tools-box');
            if (tools_box.hasClass('hide')) {
                tools_box.removeClass('hide');
                $this.removeClass('icon-edit').addClass('icon-check');
            } else {
                tools_box.addClass('hide');
                $this.removeClass('icon-check').addClass('icon-edit');
            }
        });

        //  批量删除
        content.find('.btn-batch-delete').click(function () {
            var ids = content.find('#cart_ids').val();
            if (ids === undefined || ids === '') {
                $.toast('您还没有选中宝贝哦！');
                return;
            }
            var header  = JSON.parse(content.data('headers_delete'));
            var data    = {cart_ids : ids};
            var $this   = $(this);
            $.confirm('您真的要删除选中的商品吗？', function () {
                $.showPreloader();
                var params  = {
                    header : header,
                    data : data,
                    success : function (ret) {
                        $.hidePreloader();
                        $.toast(ret.msg);
                        if(ret.code === 20000) {
                            window.location.reload();
                        }
                    }
                };
                apiPost(params);
            });
        });

        //  /产品编辑/
        $(document).on('click','.cart_cpx strong',function(){
            $(this).parent().hide();
            $(this).parent().parent().find('.delbtn').show()
        })
        $(document).on('click','.cart_newcp .go',function(){
            $(this).parent().hide();
            $(this).parent().parent().find('.cart_cpx').show()
        })
    });

    /**
     * 购物车首页
     */
    $(document).on("pageInit", "#page_cart_index1", function (e, id, page) {
        //购物车
        //  标题编辑
        $(document).on('click','.find .s',function(){
            $('.find .s').hide();
            $('.cart_cpx').hide();
            $('.find .s1').show();
            $('.delbtn').show();
        })
        $(document).on('click','.find .s1',function(){
            $('.find .s1').hide();
            $('.cart_cpx').show();
            $('.find .s').show();
            $('.delbtn').hide();
        })
        //  /产品编辑/
        $(document).on('click','.cart_cpx strong',function(){
            $(this).parent().hide();
            $(this).parent().parent().find('.delbtn').show()
        })
        $(document).on('click','.cart_newcp .go',function(){
            $(this).parent().hide();
            $(this).parent().parent().find('.cart_cpx').show()
        })

        // 勾选   计算
        $(document).on('click','.li_main .dui',function(){
            var z=parseInt($('.cart_hot1').text());
            var rmb=parseInt($(this).parent().find('.rmb1').text());
            var s=parseInt($(this).parent().find('.cp-s').text());
            var zz=rmb*s;
            if($(this).hasClass('dui_')){
                $(this).removeClass('dui_');
                $('.cart_hot1').text(z-zz)
            }else{
                $(this).addClass('dui_');
                $('.cart_hot1').text(z+zz)
            }

            $('.btns').text('结算('+$('.li_main .dui_').length+')')
        })


        //全选
        $(document).on('click','.quanx',function(){

            if($(this).hasClass('dui_')){
                $(this).removeClass('dui_');
                $('.dui').removeClass('dui_');
                $('.cart_hot1').text('0')
            }else{
                $(this).addClass('dui_');
                var number=document.getElementsByClassName('dui');
				for(i=0;i<number.length;i++){
					console.log(i)
					number[i].className='lleft dui dui_';
				}
                var length=$('.li_main .rmb1').length;
                var cplength=$('.cp-s').length;
                var zz=0;
                for(var i=0;i<length;i++){
                    zz=zz+Number($('.li_main .rmb1').eq(i).text())*Number($('.cp-s').eq(i).text());
                }
                $('.cart_hot1').text(zz)
            }
            $('.btns').text('结算('+$('.li_main .dui_').length+')')
        })

        //店铺勾选
        $(document).on('click','.cart_title .dui',function(){
            if($(this).hasClass('dui_')){
                $(this).parent().parent().find('.dui').removeClass('dui_');
            }else{
                $(this).parent().parent().find('.dui').addClass('dui_');
            }
            var dui=$('.cart_newcp .dui_').length;
            var zz=0;
            for(var i=0;i<dui;i++){
                zz=zz+
                    Number($('.cart_newcp .dui_').eq(i).parent().find('.rmb1').text())*
                    Number($('.cart_newcp .dui_').eq(i).parent().find('.cp-s').text());
            }
            $('.cart_hot1').text(zz)
            $('.btns').text('结算('+$('.li_main .dui_').length+')')
        })



        //产品属性
        $(document).on('click','.bot',function(){
            var n=$('.bot').index(this);
            $('.fix').hide();
            $('.fix').eq(n).slideDown()
        })

        $(document).on('click','.close-panel.pull-right,.button-danger.close-panel',function(){
            $('.fix').hide();
        })

        //选择按钮上色
        $(document).on('click','.clearfix a',function(){
            $(this).parent().find('a').removeClass('active');
            $(this).addClass('active');
        })
        //颜色
        $(document).on('click','.color-main a',function(){
            var parent=$(this).parent().parent().parent().parent().parent();
            parent.find('.color1').text($(this).text());
            parent.find('.color2').text($(this).text())
        })
        //选择码数
        $(document).on('click','.ma-main a',function(){
            var parent=$(this).parent().parent().parent().parent().parent();
            parent.find('.cart-ma').text($(this).text());
            parent.find('.cart-ma1').text($(this).text())

        })
        //+ -
        $(document).on('click','.jia,.cart_newcp .icon_plus',function(){
            var parent=$(this).parent().parent().parent().parent().parent();
            var n=parseInt(parent.find('.shu').text())
            var rmb=Number(parent.find('.rmb1').text());
            var z=parseInt($('.cart_hot1').text());
            parent.find('.shu').text(n+1)
            parent.find('.cp-s').text(n+1)
            parent.find('.group_cat input').val(n+1)
            if(parent.find('.dui').hasClass('dui_')){
                $('.cart_hot1').text(z+rmb)
            }
        })
        $(document).on('click','.jian,.cart_newcp .icon_reduce',function(){
            var parent=$(this).parent().parent().parent().parent().parent();
            var n=Number(parent.find('.shu').text())
            if(n!=1){
                var rmb=Number(parent.find('.rmb1').text());
                var z=parseInt($('.cart_hot1').text());
                parent.find('.shu').text(n-1)
                parent.find('.cp-s').text(n-1)
                parent.find('.group_cat input').val(n-1)
                if(parent.find('.dui').hasClass('dui_')){
                    $('.cart_hot1').text(z-rmb)
                }
            }

        })
    });

    /**
     * 确认订单页面
     */
    $(document).on("pageInit", "#page_cart_confirm", function (e, id, page) {
        var content = $("#" + id);
        $('.dizhi').click(function(){
            if($(this).hasClass('dizhi1')){
                $(this).removeClass('dizhi1');
            }else{
                $(this).addClass('dizhi1');
            }
        });
        //  选择快递方式
        content.find('.btn-choose-express-type').click(function () {
            var $this = $(this);
            var buttons1 = [
                {
                    text: '请选择',
                    label: true
                },
                {
                    text: '快递',
                    onClick: function() {
                        $this.closest('div').find(".btn-choose-express-type-input").val(1);
                        var express_amount  = Number($this.data('express'));
                        var ems_amount  = Number($this.data('ems'));
                        var amount  = Number($this.closest('li').find('.shop-express-amount').html());
                        calcShopAmount($this, express_amount, amount);
                        $this.html('快递');
                    }
                },
                {
                    text: 'EMS',
                    onClick: function() {
                        $this.closest('div').find(".btn-choose-express-type-input").val(2);
                        var express_amount  = Number($this.data('express'));
                        var ems_amount  = Number($this.data('ems'));
                        var amount  = Number($this.closest('li').find('.shop-express-amount').html());
                        calcShopAmount($this, ems_amount, amount);
                        $this.html('EMS');
                    }
                }
            ];
            var buttons2 = [
                {
                    text: '取消',
                    bg: 'danger'
                }
            ];
            var groups = [buttons1, buttons2];
            $.actions(groups);
        });
        //  商家总金额计算
        var calcShopAmount  = function (that, to_amount, amount) {
            var shop_amount = Number(that.closest('li').find('.shop-amount').html());
            var calc_after_amount   = to_amount-amount;
            shop_amount += calc_after_amount;
            that.closest('li').find('.shop-amount').html(shop_amount.toFixed(2));
            that.closest('li').find('.shop-express-amount').html(to_amount.toFixed(2));
            calcTotalAmount(calc_after_amount);
        };
        //  总金额计算
        var calcTotalAmount = function (calc_after_amount) {
            var total_amount = Number(content.find('.total-amount').html());
            content.find('.total-amount').html((total_amount + calc_after_amount).toFixed(2));
        };

        //  创建订单
        content.find('.btn-submit').click(function () {
            $.showPreloader('订单提交中...');
            var header  = JSON.parse(content.data('headers_header'));
            var data    = content.find('form').serialize();
            var params  = {
                header : header,
                data : data,
                success : function (ret) {
                    $.hidePreloader();
                    if (ret.code === 20000) {
                        var url = '/pay/multiple?orders_no=' + ret.data.orders_no;
                        $.router.load(url, true);
                    } else {
                        $.toast(ret.msg);
                    }
                }
            };
            apiPost(params);
        });
		$(document).on('click','.icon-message',function () {
		    var msg = $(this).attr('date-msg');
			$.modal({
                text : msg,
                extraClass : 'text-left',
                buttons : [
                    {
                        text : '已了解',
                        close : true
                    }
                ]
            });
		});
    });

    /**
     * 收货地址首页
     */
    $(document).on('pageInit', '#page_address_index', function (e, id, page) {
        var content = $("#" + id);
        //  设置默认地址
        content.find('.dui').click(function () {
            var header  = JSON.parse(content.data('headers_default'));
            var id      = $(this).data('id');
            var $this   = $(this);
            $.confirm('确定要把当前地址设置为默认地址吗？', function () {
                var params  = {
                    header : header,
                    data : {id : id},
                    success : function (ret) {
                        $.toast(ret.msg);
                        if (ret.code === 20000) {
                            console.log(ret);
                            content.find('.dui').removeClass('dui_');
                            $this.addClass('dui_');
                        }
                    }
                };
                apiPost(params);
            });
        });

        // 删除收货地址
        content.find('.btn-delete-address').click(function () {
            var header  = JSON.parse(content.data('headers_delete'));
            var id      = $(this).data('id');
            $.confirm('您真的要删除吗？', function () {
                var params  = {
                    header : header,
                    data : {id : id},
                    success : function (ret) {
                        if (ret.code == 20000) {
                            window.location.reload();
                        }
                        $.toast(ret.msg);
                    }
                };
                apiPost(params);
            });
        });
    });

    /**
     * 收货地址添加
     */
    $(document).on('pageInit', "#page_address_create", function (e, id, page) {
        var content = $("#" + id);
        $('.dizhi').click(function(){
            if($(this).hasClass('dizhi1')){
                $(this).removeClass('dizhi1');
                $(this).find('input').val(0);
            }else{
                $(this).addClass('dizhi1');
                $(this).find('input').val(1);
            }
        });


        content.find("#city-picker").cityPicker({
            value: ['北京', '市辖区', '东城区']
        });

        content.find("#town-picker").click(function () {
            var district = content.find("#district_id").val();
            var header  = JSON.parse(content.data('headers_town'));
            var value = [];
            var ids = [];
            var params = {
                header : header,
                data : {id : district},
                success : function (ret) {
                    if (ret.code === 20000) {
                        for (var i in ret.data) {
                            value.push(ret.data[i].a_name);
                            ids.push(ret.data[i].id);
                        }
                        content.find('#town-picker').townPicker({
                            cols: [
                                {
                                    textAlign: 'center',
                                    values: value,
                                    ids : ids
                                }
                            ]
                        });
                    } else {
                        content.find('#town-picker').html('无需选择');
                        content.find('#town-picker').townPicker({
                            cols: [
                                {
                                    textAlign: 'center',
                                    values: ['无需选择'],
                                    ids : [0]
                                }
                            ]
                        });
                        // $.alert(ret.msg);
                    }
                }
            };
            apiPost(params);
        });

        $(document).on("click", ".city-picker .close-picker", function() {
            var district = content.find("#district_id").val();
            var header  = JSON.parse(content.data('headers_town'));
            var value = [];
            var ids = [];
            var params = {
                header : header,
                data : {id : district},
                success : function (ret) {
                    if (ret.code === 20000) {
                        for (var i in ret.data) {
                            value.push(ret.data[i].a_name);
                            ids.push(ret.data[i].id);
                        }
                        content.find('#town-picker').townPicker({
                            cols: [
                                {
                                    textAlign: 'center',
                                    values: value,
                                    ids : ids
                                }
                            ]
                        });
                    } else {
                        // $.alert(ret.msg);
                        content.find('#town-picker').html('无需选择');
                        content.find('#town-picker').townPicker({
                            cols: [
                                {
                                    textAlign: 'center',
                                    values: ['无需选择'],
                                    ids : [0]
                                }
                            ]
                        });
                    }
                }
            };
            apiPost(params);
        });

        //  添加收货地址
        content.find(".btn-submit").click(function () {
            var header  = JSON.parse(content.data('headers_create'));
            var data    = content.find('form').serialize();
            var params  = {
                header : header,
                data : data,
                success : function (ret) {
                    $.toast(ret.msg)
                    if (ret.code === 20000) {
                        $.router.back();
                    }
                },
            }
            apiPost(params);
        });
    });

    /**
     * 收货地址编辑
     */
    $(document).on('pageInit', '#page_address_edit', function (e, id, page) {
        var content = $("#" + id);
        $('.dizhi').click(function(){
            if($(this).hasClass('dizhi1')){
                $(this).removeClass('dizhi1');
                $(this).find('input').val('0');
            }else{
                $(this).addClass('dizhi1');
                $(this).find('input').val('1');
            }
        });
        var districts    = content.find('.content').data();
        content.find("#city-picker").cityPicker({
            value: [districts['province'], districts['city'], districts['district']]
        });

        //var district = $("#district_id").val();
        //  设置街道

        content.find("#town-picker").click(function () {
            var district = content.find("#district_id").val();
            var header  = JSON.parse(content.data('headers_town'));
            var params = {
                header : header,
                data : {id : district},
                success : function (ret) {
                    if (ret.code === 20000) {
                        var value = [];
                        var ids = [];
                        for (var i in ret.data) {
                            value.push(ret.data[i].a_name);
                            ids.push(ret.data[i].id);
                        }
                        content.find('#town-picker').townPicker({
                            cols: [
                                {
                                    textAlign: 'center',
                                    values: value,
                                    ids : ids
                                }
                            ]
                        });
                    } else {
                        content.find('#town-picker').html('无需选择');
                        content.find('#town-picker').townPicker({
                            cols: [
                                {
                                    textAlign: 'center',
                                    values: ['无需选择'],
                                    ids : [0]
                                }
                            ]
                        });
                    }
                }
            };
            apiPost(params);
        });

        $(document).on("click", ".city-picker .close-picker", function() {
            var district = content.find("#district_id").val();
            var header  = JSON.parse(content.data('headers_town'));
            var params = {
                header : header,
                data : {id : district},
                success : function (ret) {
                    if (ret.code === 20000) {
                        var value = [];
                        var ids = [];
                        for (var i in ret.data) {
                            value.push(ret.data[i].a_name);
                            ids.push(ret.data[i].id);
                        }
                        content.find('#town-picker').townPicker({
                            cols: [
                                {
                                    textAlign: 'center',
                                    values: value,
                                    ids : ids
                                }
                            ]
                        });
                    } else {
                        content.find('#town-picker').html('无需选择');
                        content.find('#town-picker').townPicker({
                            cols: [
                                {
                                    textAlign: 'center',
                                    values: ['无需选择'],
                                    ids : [0]
                                }
                            ]
                        });
                    }
                }
            };
            apiPost(params);
        });

        //  修改收货地址
        content.find(".btn-submit").click(function () {
            var header  = JSON.parse(content.data('headers_modify'));
            var data    = content.find('form').serialize();
            var params  = {
                header : header,
                data : data,
                success : function (ret) {
                    $.toast(ret.msg)
                    if (ret.code === 20000) {
                        $.router.back();
                    }
                },
            }
            apiPost(params);
        });
    });

    /**
     * 用户个人注册
     */
    $(document).on('pageInit', '#page_user_register', function (e, id, page) {
        //协议打勾
        $(document).on('click','.xieyi',function(){
            if($(this).hasClass('xieyi-dui')){
                $(this).removeClass('xieyi-dui');

            }else{
                $(this).addClass('xieyi-dui');
            }
        })
        // 验证码发送
        var shi_t = $('.yan').data('space_time');
        // alert(shi_t);
        var shi = shi_t;
        $('.yan').on('click',function(){
            // alert($('#register').val());
            var param = {
                header : JSON.parse($('#headers1').val()),
                data : {
                    user_mobile : $('input[name="user_mobile"]').val(),
                    id:1,
                },
                success:function(ret){
                    $.alert(ret.msg);
                    if ( ret.code == 20000 ) {
                        if(shi == shi_t){
                            var time = setInterval(function(){
                                shi--;
                                $('.yan').text('获取验证码(' + shi + ')').css('background','#D6D6D6')
                                if(shi == 0){
                                    shi = shi_t;
                                    clearInterval(time);
                                    $('.yan').text('获取验证码').css('background','#40abf3')
                                }
                            },1000);
                        }
                    }
                },
                log : true,
            };
            apiPost(param);
        });
        // 注册
        $('#register-btn').on('click', function () {
            if ($('.xieyi').hasClass('xieyi-dui') == false) {
                $.alert('请先同意注册协议');
                return false;
            }
            var param = {
                header : JSON.parse($('#headers0').val()),
                data : {
                    user_mobile : $('input[name="user_mobile"]').val(),
                    user_username : $('input[name="user_username"]').val(),
                    code : $('input[name="sms_code"]').val(),
                    user_password : $('input[name="user_password"]').val(),
                    re_user_password : $('input[name="re_user_password"]').val(),
                },
                success:function(ret){
                    if ( ret.code == 20000 ) {
                        $.alert('注册成功！');
                        $.router.load('/member', true);
                    } else {
                        $.alert(ret.msg);
                    }
                },
                log : true,
            };
            apiPost(param);
        });
    });

    /**
     * 用户登录
     */
    $(document).on('pageInit', '#page_user_login', function (e, id, page) {
        // alert(1);
        $('.modal-overlay-visible').remove();
        //点击密码
        $(document).on('click','.lkai',function(){
            if($(this).hasClass('lkai1')){
                $(this).removeClass('lkai1');
                $('.lmi').prop('type', 'password')
            }else{
                $(this).addClass('lkai1');
                $('.lmi').prop('type', 'text');
            }
        })
        // 点击验证码
        $(document).on('click','.captcha_code',function(){
            var src = $(this).data('src') + '?' + Math.random();
            $(this).attr('src',src);
        });
        //
        $('#login-btn').on('click', function () {
            var param = {
                header : JSON.parse($('#login').val()),
                data : {
                    account : $('input[name="account"]').val(),
                    load_psw : $('input[name="load_psw"]').val(),
                    captcha_code : $('input[name="captcha_code"]').val(),
                },
                success:function(ret){
                    $.toast(ret.msg);
                    if ( ret.code === 20000 ) {
                        $.router.load('/member', true);
                    } else {
                        var src = $('.captcha_code').data('src') + '?' + Math.random();
                        $('.captcha_code').attr('src',src);
                    }
                },
                log : true,
            };
            apiPost(param);
        });
    });

    $(document).on('pageInit', '#page_member_index', function (e, id, page) {
        var content = $("#" + id);
        //  头部
        content.find(".content.s").on('scroll',function(){
            var viewH =$(this).height();//可见高度
            var scrollTop =$(this).scrollTop();//滚动高度
            if(scrollTop >= 200) {
                //滚动高度大于等于200的时候执行
                $('.user_head').addClass('user_head1');
                $('.user_head .logo').hide();
            }else{
                $('.user_head').removeClass('user_head1');
                $('.user_head .logo').show();
            }

        });


        var params  = {
            header : getHeader(content, 'orders_total'),
            data : {},
            success : function (ret) {

                if (ret.code === 20000) {

					if(parseInt(ret.data.wait_pay)>0){
						content.find('#wait_pay').html(ret.data.wait_pay).show();
					}
					if(parseInt(ret.data.wait_ship)>0){
						content.find('#wait_ship').html(ret.data.wait_ship).show();
					}
					if(parseInt(ret.data.wait_receive)>0){
						content.find('#wait_receive').html(ret.data.wait_receive).show();
					}
                    if(parseInt(ret.data.wait_comment)>0){
						content.find('#wait_comment').html(ret.data.wait_comment).show();
					}
                    content.find('#wait_refund').html(ret.data.refund);
                    content.find('#wait_service').html(ret.data.service);
                }
            },
            log : true
        }
        apiPost(params);
    });

    /**
     * 修改登录密码
     */
    $(document).on('pageInit', '#page_member_update_password', function (e, id, page) {

        $('.update-btn').on('click', function () {
            var param = {
                header : JSON.parse($('#headers0').val()),
                data : {
                    old_user_password : $('input[name="old_user_password"]').val(),
                    user_password : $('input[name="user_password"]').val(),
                    re_user_password : $('input[name="re_user_password"]').val(),
                },
                success:function(ret){
                    if ( ret.code == 20000 ) {
                        $.alert('修改成功');
                        $.router.load('/user/logout', true);
                    } else {
                        $.alert(ret.msg);
                    }
                },
                log : true,
            };
            apiPost(param);
        });
    });

    /**
     * 设置安全密码
     */
    $(document).on('pageInit', '#page_member_set_pay_password', function (e, id, page) {
        // 验证码发送
        var shi_t = $('.yan').data('space_time');
        var shi = shi_t;
        $('.yan').on('click',function(){
            var param = {
                header : JSON.parse($('#headers1').val()),
                data : {
                    // user_mobile : $('input[name="user_mobile"]').val(),
                    id:1,
                },
                success:function(ret){
                    $.alert(ret.msg);
                    if ( ret.code == 20000 ) {
                        if(shi == shi_t){
                            var time = setInterval(function(){
                                shi--;
                                $('.yan').text('获取验证码(' + shi + ')').css('background','#D6D6D6')
                                if(shi == 0){
                                    shi = shi_t;
                                    clearInterval(time);
                                    $('.yan').text('获取验证码').css('background','#40abf3')
                                }
                            },1000);
                        }
                    }
                },
                log : true,
            };
            apiPost(param);
        });
        $('.set-btn').on('click', function () {
            var param = {
                header : JSON.parse($('#headers0').val()),
                data : {
                    user_pay_password : $('input[name="user_pay_password"]').val(),
                    re_user_pay_password : $('input[name="re_user_pay_password"]').val(),
                    code : $('input[name="code"]').val(),
                },
                success:function(ret){
                    if ( ret.code == 20000 ) {
                        $.alert('修改成功');
                        $.router.load('/member/detail', true);
                    } else {
                        $.alert(ret.msg);
                    }
                },
                log : true,
            };
            apiPost(param);
        });
    });

    /**
     * 修改安全密码
     */
    $(document).on('pageInit', '#page_member_update_pay_password', function (e, id, page) {

        $('.update-btn').on('click', function () {
            var param = {
                header : JSON.parse($('#headers0').val()),
                data : {
                    old_user_pay_password : $('input[name="old_user_pay_password"]').val(),
                    user_pay_password : $('input[name="user_pay_password"]').val(),
                    re_user_pay_password : $('input[name="re_user_pay_password"]').val(),
                },
                success:function(ret){
                    if ( ret.code == 20000 ) {
                        $.alert('修改成功');
                        $.router.load('/member/detail', true);
                    } else {
                        $.alert(ret.msg);
                    }
                },
                log : true,
            };
            apiPost(param);
        });
    });

    /**
     * 忘记密码
     */
    $(document).on('pageInit', '#page_member_forgot_password', function (e, id, page) {
        // 验证码发送
        var shi_t = $('.yan').data('space_time');
        var shi = shi_t;
        $('.yan').on('click',function(){
            var param = {
                header : JSON.parse($('#headers1').val()),
                data : {
                    user_mobile : $('input[name="user_mobile"]').val(),
                    id:1,
                },
                success:function(ret){
                    $.alert(ret.msg);
                    if ( ret.code == 20000 ) {
                        if(shi == shi_t){
                            var time = setInterval(function(){
                                shi--;
                                $('.yan').text('获取验证码(' + shi + ')').css('background','#D6D6D6')
                                if(shi == 0){
                                    shi = shi_t;
                                    clearInterval(time);
                                    $('.yan').text('获取验证码').css('background','#40abf3')
                                }
                            },1000);
                        }
                    }
                },
                log : true,
            };
            apiPost(param);
        });
        $('.forgot-btn').on('click', function () {
            var param = {
                header : JSON.parse($('#headers0').val()),
                data : {
                    user_password : $('input[name="user_password"]').val(),
                    re_user_password : $('input[name="re_user_password"]').val(),
                    code : $('input[name="code"]').val(),
                    user_mobile : $('input[name="user_mobile"]').val(),
                },
                success:function(ret){
                    if ( ret.code == 20000 ) {
                        $.alert('修改成功');
                        $.router.load('/user/logout', true);
                    } else {
                        $.alert(ret.msg);
                    }
                },
                log : true,
            };
            apiPost(param);
        });
    });

    /**
     * 忘记安全密码
     */
    $(document).on('pageInit', '#page_member_forgot_pay_password', function (e, id, page) {
        // 验证码发送
        var shi_t = $('.yan').data('space_time');
        var shi = shi_t;
        $('.yan').on('click',function(){
            var param = {
                header : JSON.parse($('#headers1').val()),
                data : {
                    user_mobile : $('input[name="user_mobile"]').val(),
                    id:1,
                },
                success:function(ret){
                    $.alert(ret.msg);
                    if ( ret.code == 20000 ) {
                        if(shi == shi_t){
                            var time = setInterval(function(){
                                shi--;
                                $('.yan').text('获取验证码(' + shi + ')').css('background','#D6D6D6')
                                if(shi == 0){
                                    shi = shi_t;
                                    clearInterval(time);
                                    $('.yan').text('获取验证码').css('background','#40abf3')
                                }
                            },1000);
                        }
                    }
                },
                log : true,
            };
            apiPost(param);
        });
        $('.forgot-btn').on('click', function () {
            var param = {
                header : JSON.parse($('#headers0').val()),
                data : {
                    user_pay_password : $('input[name="user_pay_password"]').val(),
                    re_user_pay_password : $('input[name="re_user_pay_password"]').val(),
                    code : $('input[name="code"]').val(),
                    user_mobile : $('input[name="user_mobile"]').val(),
                },
                success:function(ret){
                    if ( ret.code == 20000 ) {
                        $.alert('修改成功');
                        $.router.load('/member/detail', true);
                    } else {
                        $.alert(ret.msg);
                    }
                },
                log : true,
            };
            apiPost(param);
        });
    });

    /**
     * 修改资料
     */
    $(document).on('pageInit', '#page_member_update_user', function (e, id, page) {
        $('.update-user').on('click', function () {
            var param = {
                header : JSON.parse($('#headers0').val()),
                data : {
                    user_nick : $('input[name="user_nick"]').val(),
                    user_avatar : $('input[name="user_avatar"]').val(),
                },
                success:function(ret){
                    if ( ret.code == 20000 ) {
                        // $.alert('修改成功');
                        $.router.load('/member/detail', true);
                    } else {
                        $.alert(ret.msg);
                    }
                },
                log : true,
            };
            apiPost(param);
        });
    });

    /**
     * 扩展信息
     */
    $(document).on('pageInit', '#page_member_extend', function (e, id, page) {
        var changeYear = false;
        // 选择生日
        $(function(){
            $("#birthday").calendar({
                value:[$('#birthday').val() == "" ? '2000-01-01': $('#birthday').val() ],
                maxDate:$('#birthday').attr('maxDate'),
                minDate:$('#birthday').attr('minDate'),
                onClose:function(){
                    // 年份变更
                    if ( baseYear != $("#birthday").val().substring(0,4)){
                        baseYear = $("#birthday").val().substring(0,4);
                        changeYear = true;
                        $('.select-category-span').html('点击选择');
                    }
                },
            });
        });
        var baseYear = $("#birthday").val() ? $("#birthday").val().substring(0,4) : 0;
        // 选择分类
        var prevYear = "";
        var vm = vms('user-extend-category', '#user-extend-category', "#" + id + " .user-extend-category", JSON.parse($('#headers1').val()) );
        $('.select-category').on('click',function(){
            var birthday = $('input[name="user_birthday"]').val();
            if ( birthday == '' ) {
                $.alert('请选择生日');
                return;
            }
            // 不是上次请求的年份
            var nowYear = birthday.substring(0,4);
            // console.log(nowYear);
            if (prevYear != nowYear) {
                changeYear = true;
                vm.request({
                    user_birthday:$('input[name="user_birthday"]').val(),
                },function(ret,vmsObject){
                    // 充值和渲染数据
                    $('.orders-list-box').children().remove();
                    if (ret.code == 20000) {
                        for( var i in ret.data ){
                            vmsObject.data.push(ret.data[i]);
                        }
                    } else {
                        $('.orders-list-box').html('无推荐偏好。');
                    }
                    setTimeout(function(){
                        // 设置默认选中
                        var have_cate = $("#have-category").val();
                        if ( have_cate != "" ) {
                            // console.log(have_cate);
                            have_cate = have_cate.split(",");
                            $('.select-category-item').each(function(){
                                if ( $.inArray($(this).val(), have_cate) != -1 ) {
                                    $(this).prop('checked', 'checked');
                                }
                            });
                        }
                    },50);
                });
                prevYear = nowYear;
            }
            // 显示框
            $.popup('.popup-about');
            $('.modal-overlay-visible').remove();
        });
        // 关闭选择分类
        $('.close-select-category').on('click',function(){
            if ( $('.select-category-item:checked').length > 0 ) {
                $('.select-category-span').html('已选择' + $('.select-category-item:checked').length + '个');
            } else {
                $('.select-category-span').html('点击选择');
            }
            $.closeModal('.popup-about');
        });
        // 保存
        $('#save_extend').on('click',function(){
            if ( $('#have-category').val() == "" ) {
                // 新增的情况
                if ($('.select-category-item:checked').length <= 0){
                    $.alert('请选择一个偏好');
                    return ;
                }
                // user_category
                var user_category = new Array;
                $('.select-category-item:checked').each(function(k,v){
                    user_category.push(v.value);
                });
            } else {
                // 年份变更
                if ( changeYear ) {
                    // alert($('.select-category-item:checked').length);
                    if ($('.select-category-item:checked').length <= 0){
                        // alert(1);
                        $.alert('请选择一个偏好');
                        return ;
                    }
                }
                // user_category
                if ( changeYear ) {
                    // 已选值
                    var user_category = new Array;
                    $('.select-category-item:checked').each(function(k,v){
                        user_category.push(v.value);
                    });
                } else {
                    // 默认值
                    var user_category = $("#have-category").val().split(",");
                }
            }
            // 设置数据
            var data = getFormJson('#user_extend');
            data.user_category = user_category;
            apiPost({
                header : JSON.parse($('#headers0').val()),
                data:data,
                log:true,
                success:function(ret){
                    if ( ret.code == 20000 ) {
                        $.router.load('/member/detail');
                    } else {
                        $.alert(ret.msg);
                    }
                },
            });
        });
    });

    /**
     * 用户宝贝创建
     */
    $(document).on('pageInit', '#page_member_baby_create', function (e, id, page) {
        // 选择生日
        $(function(){
            $("#birthday").calendar({
                value:['2010-01-01'],
            });
        });
        // 保存
        $('#save_baby').on('click',function(){
            var data = getFormJson('#user_baby');
            apiPost({
                header : JSON.parse($('#headers0').val()),
                data:data,
                log:true,
                success:function(ret){
                    $.alert(ret.msg);
                    if ( ret.code == 20000 ) {
                        $.router.load('/member/baby',true);
                    }
                },
            });
        });
    });

    /**
     * 用户宝贝更新
     */
    $(document).on('pageInit', '#page_member_baby_update', function (e, id, page) {
        // 选择生日
        $(function(){
            $("#birthday").calendar({
                value:[$('#birthday').val() == "" ? '2010-01-01': $('#birthday').val() ],
            });
        });
        // 保存
        $('#save_baby').on('click',function(){
            var data = getFormJson('#user_baby');
            apiPost({
                header : JSON.parse($('#headers0').val()),
                data:data,
                log:true,
                success:function(ret){
                    $.alert(ret.msg);
                    if ( ret.code == 20000 ) {
                        $.router.load('/member/baby',true);
                    }
                },
            });
        });
    });

    /**
     * 单订单付款
     */
    $(document).on('pageInit', '#page_pay_single', function (e, id, page) {
        var content = $("#" + id);
        var prefix  = "#" + id;
        var h = content.height();
        var set_time;
        $('.shou_fs li').click(function(){
            $('.shou_fs li').removeClass('shou_dui');
            var pay_type = $(this).addClass('shou_dui').data('id');
            $(prefix + " #single-pay-form").find('input[name="pay_type"]').val(pay_type);
        });
        $(prefix + " #single-pay-submit").click(function () {
            $.showPreloader('付款中...');
            var header  = JSON.parse($("#" + id).data('headers_pay'));
            var data    = $("#single-pay-form").serialize();
            var params  = {
                header : header,
                data : data,
                success : function (ret) {
                    $.hidePreloader();
                    if (ret.code !== 20000) {
                        $.alert(ret.msg);
                        // $.router.load('/pay/payFailed');
                    } else {
                        // $.toast(ret.msg);
                        // $.router.load('/pay/paySuccess');
                        $.closeModal();
                        window.location.href = ret.data.url;
                        return;
                        var frame   = '<iframe width="100%" height="'+h+'px" src="'+ret.data.url+'" frameborder="0"></iframe>';
                        content.find('.popup-pay .content-block').html(frame);
                        $.popup('.popup-pay');
                        $(".modal-overlay-visible").remove();

                        //  定时轮询订单状态
                        set_time = setInterval(function() {
                            getState();
                        }, 3000);

                    }
                },
                error : function (ret) {
                    $.hidePreloader();
                },
                log : true,
            };
            apiPost(params);
        });

        //  状态检测
        var getState    = function () {
            var shop_no = content.find('input[name="shop_no"]').val();
            var param   = {
                header : getHeader(content, 'state'),
                data : {shop_no : shop_no},
                success : function (ret) {
                    if (ret.code === 20000) {
                        clearInterval(set_time);
                        $.router.load('/pay/paySuccess');
                    }
                }
            };
            apiPost(param);
        };
    });

    /**
     * 多订单付款
     */
    $(document).on('pageInit', '#page_pay_multiple', function (e, id, page) {
        var content = $("#" + id);
        var h = content.height();
        var set_time;
        var prefix  = "#" + id;
        $('.shou_fs li').click(function(){
            $('.shou_fs li').removeClass('shou_dui');
            var pay_type = $(this).addClass('shou_dui').data('id');
            $(prefix + " #single-pay-form").find('input[name="pay_type"]').val(pay_type);
        });
        $(prefix + " #multiple-pay-submit").click(function () {
            $.showPreloader('付款中...');
            var header  = JSON.parse($("#" + id).data('headers_pay'));
            var data    = $("#single-pay-form").serialize();
            var params  = {
                header : header,
                data : data,
                success : function (ret) {
                    $.hidePreloader();
                    if (ret.code !== 20000) {
                        $.alert(ret.msg);
                        // $.router.load('/pay/payFailed', true);
                    } else {
                        // $.toast(ret.msg);
                        // $.router.load('/pay/paySuccess');
                        $.closeModal();
                        window.location.href = ret.data.url;
                        return;
                        var frame   = '<iframe width="100%" height="'+h+'px" src="'+ret.data.url+'&nobar=1" frameborder="0"></iframe>';
                        content.find('.popup-pay .content-block').html(frame);
                        $.popup('.popup-pay');
                        $(".modal-overlay-visible").remove();
                        //  定时轮询订单状态
                        set_time = setInterval(function() {
                            getState();
                        }, 3000);

                    }
                },
                error : function (ret) {
                    $.hidePreloader();
                },
                log : true,
            };
            apiPost(params);
        });

        //  状态检测
        var getState    = function () {
            var shop_no = content.find('input[name="shop_no"]').val();
            var param   = {
                header : getHeader(content, 'state'),
                data : {shop_no : shop_no},
                success : function (ret) {
                    if (ret.code === 20000) {
                        clearInterval(set_time);
                        $.router.load('/pay/paySuccess', true);
                    }
                }
            };
            apiPost(param);
        };
    });


    /**
     * 用户订单列表
     */
    var vm  = [];
    $(document).on('pageInit', '#page_orders_index', function (e, id, page) {
        var prefix  = "#" + id;
        var content = $("#" + id);
        //  取消订单
        $(prefix + " .orders-cancel").click(function () {
            var header  = JSON.parse($(this).data('header'));
            var no      = $(this).data('no');
            var data    = {shop_no : no}
            $.prompt('您真的要取消当前订单吗？', '取消订单', function (value) {
                var params  = {
                    header : header,
                    data : data,
                    success : function (ret) {
                        $.toast(ret.msg);
                        if (ret.code === 20000) {
                            window.location.reload();
                        }
                    },
                    log : true,
                }
                apiPost(params);
            });
        });

        //  确认收货
        $(prefix + " .orders-receive").click(function () {
            $.confirm('请您确认已收到货品，以及检查质量是否完好，确认收货后将无法退款。', '温馨提示', function() {
                $.modalPayPassword('请输入安全密码', '您正在操作确认收货', function (value) {
                    if (value === '') {
                        $.toast('安全密码不能为空');
                        return;
                    }
                    var header = JSON.parse($(prefix).data('headers_receive'));
                    var shop_no = $(prefix + " .orders-receive").data('no');
                    var data = {
                        shop_no : shop_no,
                        pay_password : value,
                    };
                    var params = {
                        header : header,
                        data : data,
                        success : function (ret) {
                            $.toast(ret.msg);
                            if (ret.code === 20000) {
                                window.location.reload();
                            }
                        },
                        log :true
                    };
                    apiPost(params);
                });
            });
        });

        //  提醒发货
        $(prefix + " .orders-notice-ship").click(function () {
            var header  = JSON.parse($("#" + id).data('headers_notice_ship'));
            var no      = $(this).data('no');
            var data    = {shop_no : no};
            var params  = {
                header : header,
                data : data,
                success : function (ret) {
                    $.toast(ret.msg);
                    if (ret.code === 20000) {
                        //window.location.reload();
                    }
                },
                log : true
            }
            apiPost(params);
        });

        var tabs_count  = $(prefix + " .buttons-tab a").size();
        var methods = {
            cancelOrders : function (e) {
                var no = $(e.srcElement).data('no');
                var data = {shop_no : no};
                var header  = getHeader(content, 'cancel');
                $.prompt('请输入取消原因', '取消订单', function (value) {
                // $.confirm('您真的要取消当前订单吗？', '取消订单', function (value) {
                    if (value === '') {
                        $.toast('取消原因不能为空');
                        return;
                    }
                    data.remark     = value;
                    // return;
                    var params = {
                        header: header,
                        data: data,
                        success: function (ret) {
                            $.toast(ret.msg);
                            if (ret.code === 20000) {
                                window.location.reload();
                            }
                        },
                        log: true
                    };
                    apiPost(params);
                });
            },
            noticeShip : function (e) {
                var no = $(e.srcElement).data('no');
                var data = {shop_no : no};
                var header  = getHeader(content, 'notice_ship');
                var params  = {
                    header : header,
                    data : data,
                    success : function (ret) {
                        $.toast(ret.msg);
                        if (ret.code === 20000) {
                            window.location.reload();
                        }
                    },
                    log : true
                }
                apiPost(params);
            },
            receiveOrders : function (e) {
                $.confirm('请您确认已收到货品，以及检查质量是否完好，确认收货后将无法退款，点击确定继续操作。', '温馨提示', function() {
                    var no = $(e.srcElement).data('no');
                    var header = getHeader(content, 'receive');
                    var uri = location.search;
                    $.modalPayPassword('请输入安全密码', '您正在操作确认收货', function (value) {
                        if (value === '' || value === undefined) {
                            $.toast('安全密码不能为空');
                            return;
                        }
                        var data = {
                            shop_no: no,
                            pay_password: value,
                        };
                        var params = {
                            header: header,
                            data: data,
                            success: function (ret) {
                                $.toast(ret.msg);
                                if (ret.code === 20000) {
                                    window.location.reload();
                                    //$.router.load('/orders' + uri)
                                }
                            },
                            log: true
                        }
                        apiPost(params);
                    })
                });
            }
        };
        var tab1_html = content.find('#tab1 .orders-list-box').html();
        // console.log(vm[1]);
        if (tab1_html === null) {
            for (var i = 1; i <= tabs_count; i++) {
                vm[i] = vms('orders-list', '#orders-list', "#" + id + " #tab" + i, '/orders', undefined, methods);
                // console.log(vm[i])
                // console.log(vm[i].$el)
            }
            var active_fun = function (that) {
                var $this = $(that);
                var mount = $this.attr('href');
                var state = $this.data('state');
                var page = $(mount).data('page');
                var data = {state: state, page : page}
                //  如果时第一页则请求
                if (page === 1) {
                    switch (state) {
                        case 1:
                            vm[2].request(data);
                            break;
                        case 2:
                            vm[3].request(data);
                            break;
                        case 3:
                            vm[4].request(data);
                            break;
                        case 4:
                            vm[5].request(data);
                            break;
                        default :
                            break;
                    }
                }
            };
            var state = location.search;
            var tab = 0;
            if (state.indexOf('?') !== -1) {
                var states = state.split('=');
                state = states[2];
                tab = parseInt(state) + 1;
            }
            if (tab > 0 && tab < 6) {
                content.find('.buttons-tab a[href="#tab' + tab + '"]').click();
                content.find('.buttons-tab a[href="#tab' + tab + '"]').trigger('click', active_fun(content.find('.buttons-tab a[href="#tab' + tab + '"]')));
            } else {
                vm[1].request({page: 1});
            }

            $(prefix + " .buttons-tab a").click(function () {
                var mount = $(this).attr('href');
                var state = $(this).data('state');
                var page = $(mount).data('page');
                var data = {state: state, page: page}
                //  如果时第一页则请求
                if (page === 1) {
                    switch (state) {
                        case 1:
                            vm[2].request(data);
                            break;
                        case 2:
                            vm[3].request(data);
                            break;
                        case 3:
                            vm[4].request(data);
                            break;
                        case 4:
                            vm[5].request(data);
                            break;
                        default :
                            vm[1].request({page: 1});
                            break;
                    }
                }
            });
        }
        //  上拉加载
        var loading = false;
        // 注册'infinite'事件处理函数
        $(document).on('infinite', '.infinite-scroll-bottom',function() {
            var state       = $(prefix + " .buttons-tab .active").data('state');
            var container   = $(prefix + " .buttons-tab .active").attr('href');
            var request     = $(container).data('request');
            var page        = parseInt($(container).data('page'));
            if (loading || request === 2) return;
            loading = true;
            var data    = {state : state, page : page};
            switch (state) {
                case 1:
                    vm[2].request(data);
                    break;
                case 2:
                    vm[3].request(data);
                    break;
                case 3:
                    vm[4].request(data);
                    break;
                case 4:
                    vm[5].request(data);
                    break;
                default :
                    vm[1].request(data);
                    break;
            }
            loading = false;
            //容器发生改变,如果是js滚动，需要刷新滚动
            $.refreshScroller();
        });
    });

    /**
     * 用户订单详情
     */
    $(document).on('pageInit', '#page_orders_detail', function (e, id, page) {
        var content = $("#" + id);
        var prefix  = "#" + id;
        //  取消订单
        content.find(".orders-cancel").click(function () {
            var header  = JSON.parse($(this).data('header'));
            var no      = $(this).data('no');
            var data    = {shop_no : no}
            $.prompt('请输入取消原因', '取消订单', function (value) {
                if (value === '') {
                    $.toast('取消原因不能为空');
                    return;
                }
                data.remark = value;
                var params  = {
                    header : header,
                    data : data,
                    success : function (ret) {
                        $.toast(ret.msg);
                        if (ret.code === 20000) {
                            window.location.reload();
                        }
                    },
                    log : true,
                }
                apiPost(params);
            });
        });

        //  确认收货
        content.find(".orders-receive").click(function () {
            $.confirm('请您确认已收到货品，以及检查质量是否完好，确认收货后将无法退款，点击确定后继续操作。', '温馨提示', function() {
                $.modalPayPassword('请输入安全密码', '您正在操作确认收货', function (value) {
                    if (value === '') {
                        $.toast('安全密码不能为空');
                    }
                    var header = JSON.parse($(prefix).data('headers_receive'));
                    var shop_no = $(prefix + " .orders-receive").data('no');
                    var data = {
                        shop_no: shop_no,
                        pay_password: value,
                    };
                    var params = {
                        header: header,
                        data: data,
                        success: function (ret) {
                            $.toast(ret.msg);
                            if (ret.code === 20000) {
                                window.location.reload();
                            }
                        },
                        log: true
                    };
                    apiPost(params);
                });
            });
        });

        //  提醒发货
        content.find(".orders-notice-ship").click(function () {
            var header  = JSON.parse($("#" + id).data('headers_notice_ship'));
            var no      = $(this).data('no');
            var data    = {shop_no : no};
            var params  = {
                header : header,
                data : data,
                success : function (ret) {
                    $.toast(ret.msg);
                    if (ret.code === 20000) {
                        window.location.reload();
                    }
                },
                log : true
            }
            apiPost(params);
        });
    });


    /**
     * 订单评价
     */
    $(document).on('pageInit', '#page_orders_comments', function (e, id, page) {
        var content = $("#" + id);
        content.find('.evaluation-box span').click(function () {
            content.find('.evaluation-box span').removeClass('dui_');
            $(this).addClass('dui_').closest('div').find('input').val($(this).data('id'));
        });

        content.find('.fraction-box img').click(function () {
            var parent  = $(this).closest('div');
            for(var i=1; i<=5; i++ ){
                if (i > $(this).index()) {
                    parent.find('img').eq(i-1).attr({'src':'/static/wap/images/icon/ic_star.png'});
                } else {
                    parent.find('img').eq(i-1).attr({'src':'/static/wap/images/icon/ic_star_a.png'});
                }
            }
            parent.find('input').val($(this).index());
        });

        //  匿名
        content.find('.camera_checkbox').click(function () {
            if ($(this).find('.dui').hasClass('dui_')) {
                $(this).find('.dui').removeClass('dui_')
                content.find('input[name="is_anonymous"]').val(0);
            } else {
                $(this).find('.dui').addClass('dui_')
                content.find('input[name="is_anonymous"]').val(1);
            }
        });

        content.find('.btn-submit').click(function () {
            var params  = {
                header : getHeader(content, 'header'),
                data : $(this).closest('form').serialize(),
                success : function (ret) {
                    $.toast(ret.msg);
                    if (ret.code === 20000) {
                        window.location.reload();
                    }
                }
            }
            apiPost(params)
        });
    });


    /**
     * 商品 - 类目
     */
    $(document).on('pageInit', '#page_category_index', function (e, id, page) {
        var content = $("#" + id);
        content.find('.btn-search').click(function () {
            var q = content.find("#search").val();
            $.router.load('/search/goods?q=' + q);
        });
        $('.three-item').hide();
        $('.three-item').first().show();
        $(document).on('click','.flist li',function(){
            var n = $('.flist li').index(this);
            $('.flist li').removeClass('hot');
            $(this).addClass('hot');
            $('.three-item').hide();
            $('.three-item').eq(n).show();
        });
        $('.icon-search').on('click',function(){
            var search = $('#search').val();
            $.router.load('/search/goods?q=' + search, true);
        });
        content.find("#search").click(function () {
            $.router.load('/search');
        });
    });

    /**
     * 搜索首页
     */
    $(document).on('pageInit', '#page_search_index', function (e, id, page) {
        var content = $("#" + id);
        content.find('.btn-search').click(function () {
            var q = content.find("#search").val();
            $.router.load('/search/goods?q=' + q);
        });

        content.find('.search-words').click(function () {
            var url = $(this).data('url');
            $.router.load(url);
        });

        $('.set_bg').keydown(function(e){
            if( e.keyCode==13 ){
                var keywords    = content.find("input[id='search']").val();
                $.router.load('/search/goods?q=' + keywords, true);
            }
        });

        $(document).on('click', '#search', function (e) {
            var keywords    = content.find("input[id='search']").val();
            if (keywords !== '' && keywords !== undefined) {
                content.find('.keywords-box').html(keywords);
                $('.search_result').show();
                if(!$("#search").val()){
                    $('.search_result').hide();
                }
                content.find('.search-goods').attr('data-url', '/search/goods?q=' + keywords);
                content.find('.search-shop').attr('data-url', '/search/shop?q=' + keywords);
            }
        });
        content.find(".search-submit").click(function () {
            var url = $(this).data('url');
            $.router.load(url);
        });
        $(document).on('input propertychange', '#search', function(e) {
            var keywords    = content.find("input[id='search']").val();
            if (keywords !== '' && keywords !== undefined) {
                content.find('.keywords-box').html(keywords);
                $('.search_result').show();
                if(!$("#search").val()){
                    $('.search_result').hide();
                }
                content.find('.search-goods').attr('data-url', '/search/goods?q=' + keywords);
                content.find('.search-shop').attr('data-url', '/search/shop?q=' + keywords);
            } else {
                $(this).siblings('.home_title').find('input').val('')
                content.find('.keywords-box').html('');
                $('.search_result').hide();
            }
        });
        $('#search_cancel').click(function(){
            content.find("input[id='search']").val('');
            content.find('.keywords-box').html('');
            $('.search_result').hide();
        });


        /**
         * 清除搜索历史
         */
        content.find('.btn-flush-history').click(function () {
            $.confirm('您真的要清空搜索记录吗？', function () {
                var params  = {
                    header : getHeader(content, 'flush'),
                    data : {},
                    success : function (ret) {
                        $.toast(ret.msg);
                        if (ret.code === 20000) {
                            window.location.reload();
                        }
                    },
                    log : true,
                }
                apiPost(params);
            });
        });
    });

    /**
     * 搜索列表页面
     */
    $(document).on('pageInit', '#page_search_goods', function (e, id, page) {
        // if (is_inline_page === true) return;
        var content = $("#" + id);
        //  搜索
        content.find('.btn-search').click(function () {
            var q = content.find("#search").val();
            $.router.load('/search/goods?q=' + q);
        });
        //  排序
        var api_url = window.location.href;
        content.find('.order-tab').click(function () {
            var url = $(this).data('url');
            $.router.load(url);
        });

        $('.set_bg').keydown(function(e){
            if( e.keyCode==13 ){
                var keywords    = content.find("input[id='search']").val();
                $.router.load('/search/goods?q=' + keywords, true);
            }
        });

        content.find('.search-sort-box').find('.text_red').removeClass('text_red');
        content.find('.search-sort-box').find('.active').removeClass('active');
        var sort    = content.find('#sort').val();
        switch(sort) {
            case 'price_asc':
                var order = 'asc';
            case 'price_desc':
                if (order !== undefined) {
                    content.find('.tab-link-price').addClass('icon_tow_b').removeClass('icon_tow_a');
                } else {
                    content.find('.tab-link-price').addClass('icon_tow_a').removeClass('icon_tow_b');
                }
                content.find('.tab-link-price').addClass('active');
                break;
            case 'sales':
                content.find('.tab-link-sales').addClass('active');
                break;
            case 'shopping_score_desc':
                var order = 'desc';
            case 'shopping_score_asc':
                if (order !== undefined) {
                    content.find('.tab-link-discount-desc').addClass('text_red');
                } else {
                    content.find('.tab-link-discount-asc').addClass('text_red');
                }
                content.find('.tab-link-discount').addClass('active');
                break;
            case 'fraction':
                var order = 'fraction';
            default:
                if (order !== undefined) {
                    content.find('.tab-link-default-' + order).addClass('text_red');
                } else {
                    content.find('.tab-link-default-default').addClass('text_red');
                }
                content.find('.tab-link-default').addClass('active');
                break;
        }

        //搜索改版
        $(function(){
            $(document).on("click", ".search_set_icon a.button", function (e) { //属性选择高亮状态
                $(this).addClass('active').siblings().removeClass('active') //当前高亮状态
                $(this).siblings('.icon_tow').removeClass('icon_tow_b') //初始化价格（icon_tow）状态
                $(this).siblings().find('.ab').hide() //关闭弹窗
                $('.mask_page_bottom').removeClass('modal-overlay-visible') //关闭遮罩
                if( $(this).find('.ab').html() ){ //是否有弹窗层
                    $(document).unbind('scroll')
                    $(this).find('.ab').show()
                    $('.mask_page_bottom').addClass('modal-overlay-visible')
                }
            });

            $(document).on("click", ".mask_page_bottom", function (e) { //关闭遮罩及弹窗
                $('.search_set_icon .ab').hide()
                $('.mask_page_bottom').removeClass('modal-overlay-visible')
            });

            $(document).on("click", ".search_set_icon p", function (e) { //关闭遮罩及弹窗
                event.stopPropagation();
                $(this).addClass('text_red').siblings().removeClass('text_red')
                $('.search_set_icon .ab').hide()
                $('.mask_page_bottom').removeClass('modal-overlay-visible')
            });

            $(document).on("click", ".panel_set .search_fast", function (e) { //侧边内选择
                $('.panel_set .search_fast').removeClass('active');
                $(this).addClass('active')
            });

            $(document).on("click", ".icon_tow", function (e) { //价格选择高低状态
                $(this).toggleClass('icon_tow_b');
            });

            $(document).on("click", ".panel_set .search_fast", function (e) { //右侧弹层选项高亮选择
                $('.panel_set .search_fast').removeClass('active');
                $(this).addClass('active')
            });
        });


        $('.btn-filter').click(function () {
            var pathinfo    = content.find('input[name="pathinfo"]').val();
            pathinfo    = pathinfo.replace('.html', '');
            var from= $(this).closest('form');
            var url = from.data('url');
            var data= from.serialize();
            var params = data.split("&");
            // var host = location.search;
            var pathinfos   = pathinfo.split('/');
            //if (host !== '' && host.indexOf("?") !== -1) {
            var uri = '/search/goods';
            var p = '';
            for (var i in params) {
                var params_arr = params[i].split('=');
                if (params_arr[1] !== '') {
                    p += '/' + params_arr[0] + '/' + params_arr[1];
                }
            }
            for (var j in pathinfos) {
                if (j % 2) {
                    continue;
                }
                var oj = parseInt(j) + 1;
                if (pathinfos[j] === 'order') {
                    uri += '/order/' + pathinfos[oj];
                } else if (pathinfos[j] !== '' && pathinfos[oj] !== '') {
                    uri += '/'+pathinfos[j]+'/' + pathinfos[oj];
                }
            }
            uri += p;
            $.router.load(uri, true);
        });

        content.find(".search-sort").click(function () {
            var url = $(this).data('url');
            window.location.href = url;
            return;
            $.router.load(url, true);
        });
        //  上拉加载
        console.log("is_inline_page", is_inline_page)


         if (is_inline_page === true) {
            // return;
            //  $("#search-goods-list").find(".orders-list-box").remove()
            //  content.find('#search-goods-list').attr('data-page', 1)
            //  $("#search-goods-list .infinite-scroll-preloader").before('<goods-list1></goods-list1>');
            //  $("#search-goods-list").find(".orders-list-box").remove()
            //  vm[id].$mount("#page_search_goods #search-goods-list");
            //  vm[id].$destroy();
            //  vm[id] = undefined;
            //  vm[id].$forceUpdate();
         }
        console.log("vm[id]", vm[id])
         if (is_inline_page === false) {
             vm[id] = vms('goods-list1', '#goods-list1', "#" + id + "  #search-goods-list", api_url, 'get', '', is_inline_page);
             // content.attr('data-vm', JSON.stringify(vm))
             vm[id].request({page: 1});
         }
        // vm[id].$forceUpdate();
        if (is_inline_page === true) {
            // console.log(123123213123)
            // vm[id].$destroy();

        }
         // console.log(vm[id]);
         // console.log(vm[id]);
        // if (is_inline_page === true) {
            //  上拉加载
            var loading = false;
            // 注册'infinite'事件处理函数
            $(document).on('infinite', '.infinite-scroll-bottom',function() {
                // console.log(vm[id])
                // vm[id].$destroy()
                // if (vm[id] === undefined) return;
                // if (vm._uid === 0) return;
                var request     = content.find('#search-goods-list').data('request');
                var page        = parseInt(content.find('#search-goods-list').data('page'));
                console.log("page",page)
                if (loading || request === 2) return;
                loading = true;
                var data    = {page : page};
                vm[id].request(data);
                loading = false;
                //容器发生改变,如果是js滚动，需要刷新滚动
                $.refreshScroller();
            });
        // }
        $('.icon_fork').click(function(){
            $('#search').val('')
        })

        //综合，促销…… 选中
        $('.search_top_ic >.col_4').click(function(){
            $(this).addClass('active').siblings().removeClass('active');
        })

        //综合，促销…… 子分类选中
        $('.search_ic_1').click(function(){
            $('.mask_page').addClass('modal-overlay-visible')
            $(this).find('.mask_page_smg').show()
        })
        $('.mask_page_smg >div').click(function(){
            event.stopPropagation();
            $(this).addClass('active').siblings().removeClass('active');
            $(this).parent('.mask_page_smg').hide();
            $('.mask_page').removeClass('modal-overlay-visible')
        })

        //右侧边
        $('.search_ic_2').click(function(){
            $('.mask_page').addClass('modal-overlay-visible').css({'z-index':'11'})
        })
        $('.panel_set .close-panel').click(function(){
            $('.mask_page').removeClass('modal-overlay-visible').css({'z-index':'9'})
        })

        $('.panel_set .search_fast').click(function(){
            $('.panel_set .search_fast').removeClass('active');
            $(this).addClass('active')
        })
    });


    /**
     * 搜索列表页面
     */
    $(document).on('pageInit', '#page_search_shop', function (e, id, page) {

        var content = $("#" + id);
        //  搜索
        content.find('.btn-search').click(function () {
            var q = content.find("#search").val();
            $.router.load('/search/shop?q=' + q);
        });
        //  排序
        var api_url = window.location.href;
        content.find('.order-tab').click(function () {
            var url = $(this).data('url');
            $.router.load(url);
        });

        $('.set_bg').keydown(function(e){
            if( e.keyCode==13 ){
                var keywords    = content.find("input[id='search']").val();
                $.router.load('/search/shop?q=' + keywords, true);
            }
        });

        content.find('.search-sort-box').find('.text_red').removeClass('text_red');
        content.find('.search-sort-box').find('.active').removeClass('active');
        var sort    = content.find('#sort').val();
        switch(sort) {
            case 'price_asc':
                var order = 'asc';
            case 'price_desc':
                if (order !== undefined) {
                    content.find('.tab-link-price').addClass('icon_tow_b').removeClass('icon_tow_a');
                } else {
                    content.find('.tab-link-price').addClass('icon_tow_a').removeClass('icon_tow_b');
                }
                content.find('.tab-link-price').addClass('active');
                break;
            case 'sales':
                content.find('.tab-link-sales').addClass('active');
                break;
            case 'shopping_score_desc':
                var order = 'desc';
            case 'shopping_score_asc':
                if (order !== undefined) {
                    content.find('.tab-link-discount-desc').addClass('text_red');
                } else {
                    content.find('.tab-link-discount-asc').addClass('text_red');
                }
                content.find('.tab-link-discount').addClass('active');
                break;
            case 'fraction':
                var order = 'fraction';
            default:
                if (order !== undefined) {
                    content.find('.tab-link-default-' + order).addClass('text_red');
                } else {
                    content.find('.tab-link-default-default').addClass('text_red');
                }
                content.find('.tab-link-default').addClass('active');
                break;
        }

        //搜索改版
        $(function(){
            $(document).on("click", ".search_set_icon a.button", function (e) { //属性选择高亮状态
                $(this).addClass('active').siblings().removeClass('active') //当前高亮状态
                $(this).siblings('.icon_tow').removeClass('icon_tow_b') //初始化价格（icon_tow）状态
                $(this).siblings().find('.ab').hide() //关闭弹窗
                $('.mask_page_bottom').removeClass('modal-overlay-visible') //关闭遮罩
                if( $(this).find('.ab').html() ){ //是否有弹窗层
                    $(document).unbind('scroll')
                    $(this).find('.ab').show()
                    $('.mask_page_bottom').addClass('modal-overlay-visible')
                }
            });

            $(document).on("click", ".mask_page_bottom", function (e) { //关闭遮罩及弹窗
                $('.search_set_icon .ab').hide()
                $('.mask_page_bottom').removeClass('modal-overlay-visible')
            });

            $(document).on("click", ".search_set_icon p", function (e) { //关闭遮罩及弹窗
                event.stopPropagation();
                $(this).addClass('text_red').siblings().removeClass('text_red')
                $('.search_set_icon .ab').hide()
                $('.mask_page_bottom').removeClass('modal-overlay-visible')
            });

            $(document).on("click", ".panel_set .search_fast", function (e) { //侧边内选择
                $('.panel_set .search_fast').removeClass('active');
                $(this).addClass('active')
            });

            $(document).on("click", ".icon_tow", function (e) { //价格选择高低状态
                $(this).toggleClass('icon_tow_b');
            });

            $(document).on("click", ".panel_set .search_fast", function (e) { //右侧弹层选项高亮选择
                $('.panel_set .search_fast').removeClass('active');
                $(this).addClass('active')
            });
        });


        $('.btn-filter').click(function () {
            var pathinfo    = content.find('input[name="pathinfo"]').val();
            pathinfo    = pathinfo.replace('.html', '');
            var from= $(this).closest('form');
            var url = from.data('url');
            var data= from.serialize();
            var params = data.split("&");
            // var host = location.search;
            var pathinfos   = pathinfo.split('/');
            //if (host !== '' && host.indexOf("?") !== -1) {
            var uri = '/search/shop';
            var p = '';
            for (var i in params) {
                var params_arr = params[i].split('=');
                if (params_arr[1] !== '') {
                    p += '/' + params_arr[0] + '/' + params_arr[1];
                }
            }
            for (var j in pathinfos) {
                if (j % 2) {
                    continue;
                }
                var oj = parseInt(j) + 1;
                if (pathinfos[j] === 'order') {
                    uri += '/order/' + pathinfos[oj];
                } else if (pathinfos[j] !== '' && pathinfos[oj] !== '') {
                    uri += '/'+pathinfos[j]+'/' + pathinfos[oj];
                }
            }
            uri += p;
            $.router.load(uri, true);
        });

        content.find(".search-sort").click(function () {
            var url = $(this).data('url');
            window.location.href = url;
            return;
            $.router.load(url, true);
        });
        //  上拉加载
        // if (is_inline_page === false) {
        // console.log(is_inline_page)
        var vm  = vms('shop-list', '#shop-list', "#" + id + " #search-shop-list", api_url, 'get');
        // vm.destroyed();
        // vm.$forceUpdate()
        vm.$forceUpdate();
        vm.request({page : 1});
        //  上拉加载
        var loading = false;
        // 注册'infinite'事件处理函数
        $(document).on('infinite', '.infinite-scroll-bottom',function() {
            var request     = content.find('#search-shop-list').data('request');
            var page        = parseInt(content.find('#search-shop-list').data('page'));
            if (loading || request === 2) return;
            loading = true;
            var data    = {page : page};
            vm.request(data);
            loading = false;
            //容器发生改变,如果是js滚动，需要刷新滚动
            $.refreshScroller();
        });

        // }
        $('.icon_fork').click(function(){
            $('#search').val('')
        })

        //综合，促销…… 选中
        $('.search_top_ic >.col_4').click(function(){
            $(this).addClass('active').siblings().removeClass('active');
        })

        //综合，促销…… 子分类选中
        $('.search_ic_1').click(function(){
            $('.mask_page').addClass('modal-overlay-visible')
            $(this).find('.mask_page_smg').show()
        })
        $('.mask_page_smg >div').click(function(){
            event.stopPropagation();
            $(this).addClass('active').siblings().removeClass('active');
            $(this).parent('.mask_page_smg').hide();
            $('.mask_page').removeClass('modal-overlay-visible')
        })

        //右侧边
        $('.search_ic_2').click(function(){
            $('.mask_page').addClass('modal-overlay-visible').css({'z-index':'11'})
        })
        $('.panel_set .close-panel').click(function(){
            $('.mask_page').removeClass('modal-overlay-visible').css({'z-index':'9'})
        })

        $('.panel_set .search_fast').click(function(){
            $('.panel_set .search_fast').removeClass('active');
            $(this).addClass('active')
        })
    });

    /**
     * 商品详情
     */
    $(document).on('pageInit', '#page_goods_index', function (e, id, page) {

        var content = $('#' + id);
        // 地区选择
        content.find(".city-picker-block").cityPicker({
            value: ['北京', '市辖区', '东城区']
        });
        // 计算运费
        $(document).on('click', '.city-picker .close-picker',function(){
            $.showPreloader('数据提交中...');
            apiPost({
                data:{
                    sku_id:$('#id').val(),
                    city_id:$('#city_id').val(),
                    num:1,
                    weight:$('#goods_weight').val(),
                    express_type:1,
                },
                header:getHeader(content,'headers_headers2'),
                log:true,
                success:function(ret){
                    $.hidePreloader();
                    if ( ret.code === 20000 ){
                        var html = '  运费：' + ret.data.fees;
                        if (ret.data.fees <= 0){
                            html = '<span class="text_green">包邮</span>';
                        }
                        $('#see-freight').html(html);
                    }
                },
                error:function(e){
                    $.toast('计算运费出错');
                },
            });
        })
        // 数量加减
        $(document).on('click','.group_cat.group_set .icon_reduce',function(){
            // 最大库存
            var maxnum = parseInt($('#see-goods-sku-num-hide').val());
            // 值
            var addDelNum = parseInt($('#see-goods-sku-num-text').val());
            $('#see-goods-sku-num-text').val(--addDelNum);
            if( addDelNum <= 0 ){
                $('#see-goods-sku-num-text').val(1);
            }
        });
        $(document).on('click','.group_cat.group_set .icon_plus',function(){
            // alert(1);
            // 最大库存
            var maxnum = parseInt($('#see-goods-sku-num-hide').val());
            // 值
            var addDelNum = parseInt($('#see-goods-sku-num-text').val());
            $('#see-goods-sku-num-text').val(++addDelNum);
            if( addDelNum > maxnum ){
                $.toast('超出库存');
                $('#see-goods-sku-num-text').val(maxnum > 0 ? maxnum : 1);
            }
        });
        $(document).on('change','#see-goods-sku-num-text',function(){
            // 最大库存
            var maxnum = parseInt($('#see-goods-sku-num-hide').val());
            // 值
            var addDelNum = parseInt($('#see-goods-sku-num-text').val());
            if ( isNaN(addDelNum) ) {
                addDelNum = 0;
            }
            if( addDelNum > maxnum ){
                $.toast('超出库存');
                $('#see-goods-sku-num-text').val(maxnum > 0 ? maxnum : 1);
            }
            if( addDelNum <= 0 ){
                $('#see-goods-sku-num-text').val(1);
            }
        });
        // 设置查看的模式 sku buy-now add-cart
        var seeGoodsSku = 'sku';
        $('.see-goods-sku').on('click',function(){
            seeGoodsSku = 'sku';
            $('.see-goods-sku-num').addClass('hide');
            $.popup('.popup-express02');
        });
        //  立即购买
        $(".btn-buyNow").click(function () {
            seeGoodsSku = 'buy-now';
            $('.see-goods-sku-num').removeClass('hide');
            $.popup('.popup-express02');
        });
        //  加入购物车
        $(".btn-addCart").click(function () {
            seeGoodsSku = 'add-cart';
            $('.see-goods-sku-num').removeClass('hide');
            $.popup('.popup-express02');
        });

		//头部选中 滚动到指定位置
        var isLoadGoodsContent = false;
        var isLoadGoodsPamras = false;
		$('#tab_set_red a').click(function(e){
			var a = new Array();
				a[0] = 0;
				a[1] = $('.tab_set').eq(0).height() + a[0];
				a[2] = $('.tab_set').eq(1).height() + a[1];
				a[3] = $('.tab_set').eq(2).height() + a[2];
			$(this).addClass('active').siblings().removeClass('active');
			$('.content').scrollTop( a[$(this).index()] );
		})
        // 加载商品参数
        $('a[href="#tab002"]').on('click',function(){
            if ( isLoadGoodsPamras == false ) {
                isLoadGoodsPamras = true;
                apiPost({
                    data:{
                        goods_id : $('#goods_id').val(),
                    },
                    header : getHeader(content,'headers_headers1'),
                    async: false,
                    //log:true,
                    success : function(ret){
                        if ( ret.code === 20000 ) {
                            $.each(ret.data,function(k,v){
                                var html = '<li class="mb10 over">';
                                html += '<span class="main30 fl">' + v.group_name + '</span>';
                                html += '<div class="main70 fl">' + v.group_value.replace(/\n/g,'<br>') + '</div>';
                                html += '</li>';
                                $('.goods_params').append(html);
                            });
                        }
                    },
                });
            }
        });
		//滚动监听
		$('.content').scroll(function(e){
			var a = new Array();
				a[0] = 0;
				a[1] = $('.tab_set').eq(0).height() + a[0];
				a[2] = $('.tab_set').eq(1).height() + a[1];
				a[3] = $('.tab_set').eq(2).height() + a[2];
			if( $('.content').scrollTop()<a[1] ){
				$('.tab_set_red a').eq(0).addClass('active').siblings().removeClass('active');
                // 加载商品详情
                if ( isLoadGoodsContent == false ) {
                    isLoadGoodsContent = true;
                    apiPost({
                        data:{
                            goods_id : $('#goods_id').val(),
                        },
                        header : getHeader(content,'headers_headers0'),
                        //log:true,
                        success : function(ret){
                            if ( ret.code === 20000 ) {
                                $('.goods_content').html(ret.data.goods_content);
                            }
                        },
                    });
                }
			}else if( $('.content').scrollTop()<a[2] ){
				$('.tab_set_red a').eq(1).addClass('active').siblings().removeClass('active');
			}else if( $('.content').scrollTop()<a[3] ){
				$('.tab_set_red a').eq(2).addClass('active').siblings().removeClass('active');
			}else{
				$('.tab_set_red a').eq(3).addClass('active').siblings().removeClass('active');
			}
		})

        function getSkuId(){
            var skuid = 0;
            // 获取skuid
            var ids = [];
            $('#select-goods-sku .active').each(function(){
                ids.push($(this).data('skuid'));
            });
            var skuid = 0;
            ids = ids.join(",");
            var goods_sku = JSON.parse($('#goods_sku').val());
            $.each(goods_sku,function(k,v){
                if ( v == ids ){
                    skuid = k;
                }
            });
            return skuid;
        }
        // 确定和关闭
        $('.select-goods-sku,.close-popup-popup-express02').on('click',function(){
            var skuid = getSkuId();
            // 只是查看属性
            if ( seeGoodsSku == 'sku' || true == $(this).hasClass('close-popup-popup-express02') ) {
                $.closeModal();
                if ( $('#id').val() != skuid ) {
                    setTimeout(function(){
                        //window.location.href = '/goods?id=' + skuid;
						$.router.load('/goods?id=' + skuid);
                    },500);
                }
                return ;
            }
            // 立即购买
            if ( seeGoodsSku == 'buy-now' ) {
                if ( parseInt($('#see-goods-sku-num-text').val()) < 1 ) {
                    $.alert('请选择数量');
                    return ;
                }
                $.showPreloader('数据提交中...');
                setTimeout(function(){},10);
                apiPost({
                    header : getHeader(content, 'headers_buy_now'),
                    data : {
                        goods_sku_id : getSkuId(),
                        goods_id : $('#goods_id').val(),
                        goods_num : $('#see-goods-sku-num-text').val(),
                        cps_spm : $('#cps_spm').val()
                    },
                    success : function (ret) {
                        $.hidePreloader();
                        if (ret.code === 20000) {
                            var form    = $('#form-goods-confirm');
                            form.find('input[name="cart_ids"]').val(ret.data.id);
                            form.submit();
                        } else {
                            $.toast(ret.msg);
                            if ( $('#id').val() != skuid ) {
                                setTimeout(function(){
                                    window.location.href = '/goods?id=' + skuid;
                                },500);
                            }
                        }
                    },
                    error:function(e){
                        $.hidePreloader();
                    }
                });
                $.closeModal('.popup-express02');
                return ;
            }
            // 加入购物车
            if ( seeGoodsSku == 'add-cart' ) {
                if ( parseInt($('#see-goods-sku-num-text').val()) < 1 ) {
                    $.alert('请选择数量');
                    return ;
                }
                $.showPreloader('数据提交中...');
                apiPost({
                    header : getHeader(content, 'headers_add_cart'),
                    data : {
                        goods_sku_id : getSkuId(),
                        goods_id : $('#goods_id').val(),
                        goods_num : $('#see-goods-sku-num-text').val(),
                        shop_id : $('#shop_id').val(),
                        cps_spm : $('#cps_spm').val()
                    },
                    success : function (ret) {
                        $.hidePreloader();
                        if ( ret.code == 20000 ) {
                            $.toast('成功加入购物车');
                        } else {
                            $.toast(ret.msg);
                        }
                        if ( $('#id').val() != skuid ) {
                            setTimeout(function(){
                                window.location.href = '/goods?id=' + skuid;
                            },500);
                        }
                    },
                });
                $.closeModal('.popup-express02');
            }
        });
        // 属性切换
        var GoodsSkuList = [];
        $('#select-goods-sku .search_fast').on('click',function(){
            $(this).addClass('active').siblings().removeClass('active');
            var ids = [];
            $('#select-goods-sku .active').each(function(){
                ids.push($(this).data('skuid'));
            });
            var skuid = 0;
            ids = ids.join(",");
            // alert(ids);
            var goods_sku = JSON.parse($('#goods_sku').val());
            $.each(goods_sku,function(k,v){
                if ( v == ids ){
                    skuid = k;
                }
            });
            function setGoodsSku(data){
                // console.log(data.goods_sku_num);
                var src =  data.goods_sku_album.hasOwnProperty(0) ? data.goods_sku_album[0] : $('#goods_images').val();
                $('#select-goods-sku .select-goods-sku-img').attr('src',src + '?imageMogr2/thumbnail/300x300!');
                $('#select-goods-sku .select-goods-sku-price').html(data.goods_sku_price);
                $('#select-goods-sku .select-goods-sku-num').html(data.goods_sku_num);
                // 最大库存再设置
                $('#see-goods-sku-num-hide').val(data.goods_sku_num);
                // 最大库存
                var maxnum = parseInt($('#see-goods-sku-num-hide').val());
                // 值
                var addDelNum = parseInt($('#see-goods-sku-num-text').val());
                // 变更之后是否也超出
                if( addDelNum > maxnum ){
                    $('#see-goods-sku-num-text').val(maxnum > 0 ? maxnum : 1);
                }
            }
            // console.log(skuid);
            // 请求数据
            if ( GoodsSkuList.hasOwnProperty(skuid) ) {
                setGoodsSku(GoodsSkuList[skuid]);
            } else {
                // 最大库存先重置，防止网络延迟
                $('#see-goods-sku-num-hide').val(0);
                apiPost({
                    data:{
                        id:skuid,
                    },
                    header:getHeader(content,'headers_headers5'),
                    log:true,
                    success:function(ret){
                        if ( ret.code === 20000 ) {
                            GoodsSkuList[skuid] = ret.data;
                            setGoodsSku(ret.data);
                        }
                    },
                    error:function(e){

                    },
                });
            }
        });
        // 收藏
        apiPost({
            data:{
                sku_id:$('#id').val(),
            },
            header:getHeader(content,'headers_headers7'),
            async: false,
            //log:true,
            success:function(ret){
                if ( ret.code === 20000 ) {
                    $('.attention-goods').addClass('active');
                }
            },
            error:function(e){

            },
        });

        var Attention       = false;
        $('.attention-goods').on('click',function(){
            if ( Attention == true ) {
                $.toast('...');
                return ;
            }
            Attention = true;
            // $.showPreloader('数据提交中...');
            apiPost({
                data:{
                    sku_id:$('#id').val(),
                },
                header:getHeader(content,'headers_headers6'),
                log:true,
                success:function(ret){
                    Attention = false;
                    $.toast(ret.msg);
                    if (ret.code === 20000) {
                        if (ret.data.is_attention === 1){
                            $('.attention-goods').addClass('active');
                        } else {
                            $('.attention-goods').removeClass('active');
                        }

                    }
                },
            });
        });
        // 店铺详情
        apiPost({
            data:{
                shop_id:$('#shop_id').val(),
            },
            header : getHeader(content,'headers_headers3'),
            async: false,
            //log:true,
            success : function(ret){
                if ( ret.code === 20000 ) {
                    $('.shop-name').html(ret.data.shop_name);
                    $('.shop_service_fraction').html(ret.data.shop_service_fraction);
                    $('.shop_logistics_fraction').html(ret.data.shop_logistics_fraction);
                    $('.shop_description_fraction').html(ret.data.shop_description_fraction);
                    $('.shop_synthesis_fraction').html(ret.data.shop_synthesis_fraction);
                    $('.visit-shop').attr('href', ret.data.shop_url);
                    var shopAddress = ret.data.province_name + ' ' + ret.data.city_name + ' ' + ret.data.district_name;
                    $('.shop-address').html(shopAddress);
                }
            },
        });


        content.find('#comment-tabs a').first().click();
        // 评价统计
        $(function(){
            apiPost({
                data:{
                    goods_id : content.find('#goods_id').val(),
                },
                async: false,
                header:getHeader(content, 'statistics'),
                success:function(ret){
                    if ( ret.code == 20000 ) {
                        $('.comment-all').html(ret.data.all);
                        $('.comment-good').html(ret.data.good);
                        $('.comment-middle').html(ret.data.middle);
                        $('.comment-poor').html(ret.data.poor);
                        $('.comment-images').html(ret.data.images);
                    }
                },
            });
        });
        //  评价
        var comment_tabs_count  = content.find('#comment-tabs a').size();
        var vm  = [];
        for (var i = 1; i <= comment_tabs_count; i++) {
            vm[i]   = vms('comments-list', '#comments-list', "#" + id + " #comment-tab" + i, getHeader(content, 'headers_headers4'));
        }
        var goods_id= content.find('input[id="goods_id"]').val();
        vm[1].request({page :1, goods_id : goods_id,pagesize:3});
        content.find('#comment-tabs a').click(function () {
            var state   = $(this).data('state');
            var tab     = $(this).attr('href');
            var p       = $(tab).data('page');
            var data    = {page : p, state : state, goods_id : goods_id, pagesize : 5}
            switch (state) {
                case 'images':
                    vm[2].request(data);
                    break;
                case 'good':
                    vm[3].request(data);
                    break;
                case 'middle':
                    vm[4].request(data);
                    break;
                case 'poor':
                    vm[5].request(data);
                    break;
                default :
                    vm[1].request(data);
                    break;
            }
        });
        //  上拉加载
        var loading = false;
        // 注册'infinite'事件处理函数
        $(document).on('infinite', '.infinite-scroll-bottom',function() {
            if (false === content.find('.buttons-tab a[href="#tab3"]').hasClass('active')) {
                return;
            }
            var state       = content.find('#comment-tabs a.active').data('state');
            var container   = content.find('#comment-tabs a.active').attr('href');
            var request     = $(container).data('request');
            var page        = parseInt($(container).data('page'));
            if (loading || request === 2) return;
            loading = true;
            var data    = {state : state, page : page, goods_id : goods_id};
            switch (state) {
                case 'images':
                    vm[2].request(data);
                    break;
                case 'good':
                    vm[3].request(data);
                    break;
                case 'middle':
                    vm[4].request(data);
                    break;
                case 'poor':
                    vm[5].request(data);
                    break;
                default :
                    vm[1].request(data);
                    break;
            }
            loading = false;
            //容器发生改变,如果是js滚动，需要刷新滚动
            $.refreshScroller();
        });

        // 重新设置控件
        $(function(){
            var swiper = new Swiper('.swiper-container-new', {
                autoplay: true,
                spaceBetween: 0,
                centeredSlides: true,
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
            });
        });
    });


	$(document).on('pageInit', '#page_goods_comment', function (e, id, page) {
		var content = $('#' + id);

		// 评价统计
        $(function(){
            apiPost({
                data:{
                    goods_id : content.find('#goods_id').val(),
                },
                async: false,
                header:getHeader(content, 'headers_statistics'),
                success:function(ret){
                    if ( ret.code == 20000 ) {
                        $('.comment-all').html(ret.data.all);
                        $('.comment-good').html(ret.data.good);
                        $('.comment-middle').html(ret.data.middle);
                        $('.comment-poor').html(ret.data.poor);
                        $('.comment-images').html(ret.data.images);
                    }
                },
            });

        });
		//  评价
        var comment_tabs_count  = content.find('#comment-tabs a').size();
        var vm  = [];
        for (var i = 1; i <= comment_tabs_count; i++) {
            vm[i]   = vms('comments-list', '#comments-list', "#" + id + " #comment-tab" + i, getHeader(content, 'headers_headers1'));
        }

        var goods_id= content.find('input[id="goods_id"]').val();
        vm[1].request({page :1, goods_id : goods_id});
		/*
        content.find('#comment-tabs a').click(function () {
			console.log(999);

            var state   = $(this).data('state');
            var tab     = $(this).attr('href');
            var p       = $(tab).data('page');
            var data    = {page : p, state : state, goods_id : goods_id}
            switch (state) {
                case 'images':
                    vm[2].request(data);
                    break;
                case 'good':
                    vm[3].request(data);
                    break;
                case 'middle':
                    vm[4].request(data);
                    break;
                case 'poor':
                    vm[5].request(data);
                    break;
                default :
                    vm[1].request(data);
                    break;
            }

        });
		*/
        //  上拉加载
        var loading = false;
        // 注册'infinite'事件处理函数
        $(document).on('infinite', '.infinite-scroll-bottom',function() {
            var state       = content.find('#comment-tabs a.active').data('state');
            var container   = content.find('#comment-tabs a.active').attr('href');
            var request     = $(container).data('request');
            var page        = parseInt($(container).data('page'));
            if (loading || request === 2) return;
            loading = true;
            var data    = {state : state, page : page, goods_id : goods_id};
            switch (state) {
                case 'images':
                    vm[2].request(data);
                    break;
                case 'good':
                    vm[3].request(data);
                    break;
                case 'middle':
                    vm[4].request(data);
                    break;
                case 'poor':
                    vm[5].request(data);
                    break;
                default :
                    vm[1].request(data);
                    break;
            }
            loading = false;
            //容器发生改变,如果是js滚动，需要刷新滚动
            $.refreshScroller();
        });
	});


    /**
     * 常买清单
     */
    $(document).on('pageInit', '#page_lists_index', function (e, id, page) {
        $('.icon_fork').click(function(){
            $('#search').val('')
        })

        //综合，促销…… 选中
        $('.search_top_ic >.col_4').click(function(){
            $(this).addClass('active').siblings().removeClass('active');
        })

        //综合，促销…… 子分类选中
        $('.search_ic_1').click(function(){
            $('.mask_page').addClass('modal-overlay-visible')
            $(this).find('.mask_page_smg').show()
        })
        $('.mask_page_smg >div').click(function(){
            event.stopPropagation();
            $(this).addClass('active').siblings().removeClass('active');
            $(this).parent('.mask_page_smg').hide();
            $('.mask_page').removeClass('modal-overlay-visible')
        })

        //右侧边
        $('.search_ic_2').click(function(){
            $('.mask_page').css({'z-index':'11'}).addClass('modal-overlay-visible')
        })
        $('.panel_set .close-panel').click(function(){
            $('.mask_page').css({'z-index':'9'}).removeClass('modal-overlay-visible')
        })

        $('.panel_set .search_fast').click(function(){
            $('.panel_set .search_fast').removeClass('active');
            $(this).addClass('active')
        })
    });

    /**
     * 优惠券
     */
    $(document).on('pageInit', '#page_coupon_index', function (e, id, page) {
        // 领券弹窗
        $('.active .get_coupons_right a.button').click(function(){
            $('.mask_page').css({'z-index':'5000'}).addClass('modal-overlay-visible');
            $('.mask_page_center').show()
            $(this).parents('.active').removeClass('active').addClass('active_go')
        })
        $('.mask_page_center .close-panel').click(function(){
            $(this).parents('.mask_page_center').hide();
            $('.mask_page').css({'z-index':'1000'}).removeClass('modal-overlay-visible')
        })
    });

    /**
     * 推荐
     */
    $(document).on('pageInit', '#page_recommend_index', function (e, id, page) {
        $(".swiper-container").swiper();//banner

        //综合，促销…… 选中
        $('.search_top_ic >.col_5,.hot_push >.pull-left,.mask_pager_smg >div,.mask_page_up .search_fast').click(function(){
            $(this).addClass('active').siblings().removeClass('active');
        })

        // $('').click(function(){
        //     $(this).addClass('active').siblings().removeClass('active');
        // })


        $('.panel_set .search_fast').click(function(){
            $('.panel_set .search_fast').removeClass('active');
            $(this).addClass('active')
        })


        $('#act_a').click(function(){
            $('.mask_page').css({'z-index':'5000'}).addClass('modal-overlay-visible')
            $('.act_a').show();
            $('.act_b').hide();
        })
        $('#act_b').click(function(){
            $('.mask_page').css({'z-index':'5000'}).addClass('modal-overlay-visible')
            $('.act_a').hide();
            $('.act_b').show()
        })

        // 遮罩层消失
        $('.panel_set .close-panel').click(function(){
            $('.mask_page').css({'z-index':'1000'}).removeClass('modal-overlay-visible')
        })



        // 数量加减
        $('.group_cat .icon_reduce').click(function(){
            $(this).siblings('input').val( Number($(this).siblings('input').val()) - 1 );
            if( Number($(this).siblings('input').val()) <= 0 ){
                $(this).siblings('input').val('0')
            }
        })
        $('.group_cat .icon_plus').click(function(){
            $(this).siblings('input').val( Number($(this).siblings('input').val()) + 1 );
        })

        // 底部弹窗
        $('.mask_page_up .close-panel').click(function(){
            $(this).parents('.mask_page_up').hide();
            $('.mask_page').css({'z-index':'1000'}).removeClass('modal-overlay-visible')
        })

        $('.buy_cat').click(function(){
            $('.mask_page').css({'z-index':'5000'}).addClass('modal-overlay-visible');
            $('.mask_page_up').show()
        })
    });

    /**
     * 充值
     */
    $(document).on('pageInit', '#page_recharge_index', function (e, id, page) {
        $('.icon_fork').click(function(){
            $(this).siblings('input').val('')
        })

        $('.number_money').click(function(){
            $(this).addClass('active').siblings().removeClass('active');
        })
    });

    /**
     * 促销大全
     */
    $(document).on('pageInit', '#page_promotions_index', function (e, id, page) {
        $(".swiper-container").swiper();//banner
    });

    /**
     * 申请退款
     */
    $(document).on('pageInit', '#page_refund_apply', function (e, id, page) {
        var content = $("#" + id);
        var amountData  = content.find('input[name="amount"]').data();
        var numData     = content.find('input[name="num"]').data();
        var expressData = content.find('input[name="express_amount"]').data();
        //  修改金额
        $(".change-num").change(function () {
            var num         = Number(content.find('input[name="num"]').val());
            if (num === '' || num === 0) {
                $.toast('数量不能为空且必须大于0');
                return;
            }
            num = parseInt(num);
            $(this).val(num);
            var amount      = Number(num * amountData.min).toFixed(2);
            content.find('input[name="amount"]').prev().html(amount)
            content.find('input[name="amount"]').attr('value', amount)
        });

        content.find('.btn-submit').click(function () {
            var header  = JSON.parse(content.data('headers_header'));
            var data    = content.find('form').serialize();
            var amount      = Number(content.find('input[name="amount"]').val()).toFixed(2);
            var num         = parseInt(content.find('input[name="num"]').val());
            var express     = Number(content.find('input[name="express_amount"]').val()).toFixed(2);
            //  退款类型
            switch (type) {
                case 1: //  仅退款
                    if (amount < 0) {
                        $.toast('退款金额不能小于 ' + amountData.min + ' 元');
                        return;
                    }
                    if (amount > amountData.max) {
                        $.toast('退款金额不能大于 ' + amountData.max + ' 元');
                        return;
                    }
                    break;
                case 2: //  退货退款
                    if (num > numData.max) {
                        $.toast('数量不能大于 ' + numData.max + ' 件');
                        return;
                    }
                    if (num < numData.min) {
                        $.toast('数量不能小于 ' + numData.min + ' 件');
                        return;
                    }
                    break
            }
            if (express > expressData.max) {
                $.toast('退运费不能大于 ' + expressData.max + ' 元');
                return;
            }
            if (express < expressData.min) {
                $.toast('退运费不能小于 ' + expressData.min + ' 元');
                return;
            }
            var params  = {
                header : header,
                data : data,
                success : function (ret) {
                    $.toast(ret.msg);
                    if (ret.code === 20000) {
                        $.router.load('/refund', true);
                    }
                },
                log : true,
            };
            apiPost(params);
        });

        content.find("#create-type-actions").click(function () {
            var $this   = $(this);
            var isShip  = $this.next();
            var btn1    = [
                {
                    text : '仅退款',
                    onClick : function () {
                        $this.html('仅退款');
                        content.find('input[name="num"]').val(0);
                        content.find('input[name="amount"]').val(amountData.max);
                        content.find('.refund-num-box').addClass('hide');
                        content.find('input[name="amount"]').prev().addClass('hide');
                        content.find('input[name="amount"]').removeClass('hide');
                        isShip.val(1);
                    }
                },
                {
                    text : '退货退款',
                    onClick : function () {
                        $this.html('退货退款');
                        var num = parseInt(content.find('.refund-num-box').data('num'));
                        content.find('input[name="num"]').val(numData.max);
                        content.find('input[name="amount"]').prev().html(amountData.max);
                        isShip.val(2);
                        content.find('.refund-num-box').removeClass('hide');
                        content.find('input[name="amount"]').prev().removeClass('hide');
                        content.find('input[name="amount"]').addClass('hide');
                    }
                }
            ];
            var btn2 = [
                {
                    text: '取消',
                    bg: 'danger'
                }
            ];
            var groups = [btn1, btn2];
            $.actions(groups);
        });

    });

    /**
     * 退款管理首页
     */
    $(document).on('pageInit', '#page_refund_index', function (e, id, page) {
        var content = $("#" + id);
        content.find('.btn-cancel-refund').click(function () {
            var header  = JSON.parse(content.data('headers_cancel'));
            var no      = $(this).data('no');
            $.prompt('请输入取消理由', '取消退款', function (value) {
                if (value === '' || value === undefined) {
                    $.alert('取消理由不能为空');
                    return;
                }
                var data    = {refund_no : no, remark : value};
                var params  = {
                    header : header,
                    data : data,
                    success : function (ret) {
                        $.toast(ret.msg);
                        if (ret.code === 20000) {
                            window.location.reload();
                        }
                    }
                };
                apiPost(params);
            });
        });

        var methods = {
            cancelRefund : function (e) {
                var no = $(e.srcElement).data('no');
                var header  = getHeader(content, 'cancel');
                $.prompt('请输入取消理由', '取消退款', function (value) {
                    if (value === '' || value === undefined) {
                        $.toast('取消理由不能为空');
                        return;
                    }
                    var data    = {refund_no : no, remark : value};
                    var params  = {
                        header : header,
                        data : data,
                        success : function (ret) {
                            $.toast(ret.msg);
                            if (ret.code === 20000) {
                                window.location.reload();
                            }
                        }
                    };
                    apiPost(params);
                });
            },
        };

        //  上拉加载
        var tabs_count  = content.find('.buttons-tab a').size();
        var vm  = [];
        for (var i = 1; i <= tabs_count; i++) {
            vm[i] = vms('refund-list', '#refund-list', "#" + id + " #tab" + i, '/refund', undefined, methods);
        }
        vm[1].request({page : 1});
        content.find('.buttons-tab a').click(function () {
            var mount = $(this).attr('href');
            var state = $(this).data('state');
            var page  = $(mount).data('page');
            var data  = {state : state, page : page}
            //  如果时第一页则请求
            if (page === 1) {
                switch (state) {
                    case 'buyer':
                        vm[2].request(data);
                        break;
                    case 'seller':
                        vm[3].request(data);
                        break;
                    case 20:
                        vm[4].request(data);
                        break;
                    case 100:
                        vm[5].request(data);
                        break;
                    default :
                        break;
                }
            }
        });

        //  上拉加载
        var loading = false;
        // 注册'infinite'事件处理函数
        $(document).on('infinite', '.infinite-scroll-bottom',function() {
            var state       = content.find('.buttons-tab .active').data('state');
            var container   = content.find('.buttons-tab .active').attr('href');
            var request     = $(container).data('request');
            var page        = parseInt($(container).data('page'));
            if (loading || request === 2) return;
            loading = true;
            var data    = {state : state, page : page};
            switch (state) {
                case 'buyer':
                    vm[2].request(data);
                    break;
                case 'seller':
                    vm[3].request(data);
                    break;
                case 20:
                    vm[4].request(data);
                    break;
                case 100:
                    vm[5].request(data);
                    break;
                default :
                    vm[1].request(data);
                    break;
            }
            loading = false;
            //容器发生改变,如果是js滚动，需要刷新滚动
            $.refreshScroller();
        });
    });

    /**
     * 退款邮寄商品
     */
    $(document).on('pageInit', '#page_refund_ship', function (e, id, page) {
        var content = $("#" + id);
        content.find('.btn-submit').click(function () {
            var params  = {
                header : getHeader(content, 'headers_ship'),
                data : getData(content),
                success : function (ret) {
                    $.toast(ret.msg);
                    if (ret.code === 20000) {
                        $.router.back();
                    }
                },
            };
            apiPost(params);
        });
        content.find("#express-company-picker").expressCompanyPicker({
            value: ['常用', '顺丰速运']
        });
        //express-company-picker

    });

    /**
     * 退款提起申诉
     */
    $(document).on('pageInit', '#page_refund_appeal', function (e, id, page) {
        var content = $("#" + id);
        content.find('.btn-submit').click(function () {
            var params  = {
                header : getHeader(content, 'headers_appeal'),
                data : getData(content),
                success : function (ret) {
                    $.toast(ret.msg);
                    if (ret.code === 20000) {
                        $.router.back();
                    }
                },
            };
            apiPost(params);
        });
    });

    /**
     * 退款详情
     */
    $(document).on('pageInit', '#page_refund_detail', function (e, id, page) {
        var content = $("#" + id);
        content.find('.btn-cancel-refund').click(function () {
            var header  = JSON.parse(content.data('headers_cancel'));
            var no      = $(this).data('no');
            $.prompt('请输入取消理由', '取消退款', function (value) {
                if (value == '') {
                    $.alert('取消理由不能为空');
                    return;
                }
                var data    = {refund_no : no, remark : value};
                var params  = {
                    header : header,
                    data : data,
                    success : function (ret) {
                        $.toast(ret.msg);
                        if (ret.code === 20000) {
                            window.location.reload();
                        }
                    }
                };
                apiPost(params);
            });
        });
    });

    /**
     * 退款修改
     */
    $(document).on('pageInit', '#page_refund_edit', function (e, id, page) {
        var content = $("#" + id);
        var amountData  = content.find('input[name="amount"]').data();
        var numData     = content.find('input[name="num"]').data();
        var expressData = content.find('input[name="express_amount"]').data();
        //  修改金额
        $(".change-num").change(function () {
            var num         = Number(content.find('input[name="num"]').val());
            if (num === '' || num === 0) {
                $.toast('数量不能为空且必须大于0');
                return;
            }
            num = parseInt(num);
            $(this).val(num);
            var amount      = Number(num * amountData.min).toFixed(2);
            content.find('input[name="amount"]').prev().html(amount)
            content.find('input[name="amount"]').attr('value', amount)
        });

        content.find('.btn-submit').click(function () {
            var header  = JSON.parse(content.data('headers_edit'));
            var data    = content.find('form').serialize();
            var amount      = Number(content.find('input[name="amount"]').val()).toFixed(2);
            var num         = parseInt(content.find('input[name="num"]').val());
            var express     = Number(content.find('input[name="express_amount"]').val()).toFixed(2);

            var type        = content.find('#type').val();

            //  退款类型
            switch (type) {
                case 1: //  仅退款
                    if (amount < 0) {
                        $.toast('退款金额不能小于 ' + amountData.min + ' 元');
                        return;
                    }
                    if (amount > amountData.max) {
                        $.toast('退款金额不能大于 ' + amountData.max + ' 元');
                        return;
                    }
                    break;
                case 2: //  退货退款
                    if (num > numData.max) {
                        $.toast('数量不能大于 ' + numData.max + ' 件');
                        return;
                    }
                    if (num < numData.min) {
                        $.toast('数量不能小于 ' + numData.min + ' 件');
                        return;
                    }
                    break
            }

            if (express > expressData.max) {
                $.toast('退运费不能大于 ' + expressData.max + ' 元');
                return;
            }
            if (express < expressData.min) {
                $.toast('退运费不能小于 ' + expressData.min + ' 元');
                return;
            }
            var params  = {
                header : header,
                data : data,
                success : function (ret) {
                    $.toast(ret.msg);
                    if (ret.code === 20000) {
                        $.router.load('/refund', true);
                    }
                },
                log : true,
            };
            apiPost(params);
        });

        content.find("#create-type-actions").click(function () {
            var $this   = $(this);
            var isShip  = $this.next();
            var btn1    = [
                {
                    text : '仅退款',
                    onClick : function () {
                        $this.html('仅退款');
                        content.find('input[name="num"]').val(0);
                        content.find('input[name="amount"]').val(amountData.max);
                        content.find('.refund-num-box').addClass('hide');
                        content.find('input[name="amount"]').prev().addClass('hide');
                        content.find('input[name="amount"]').removeClass('hide');
                        isShip.val(1);
                    }
                },
                {
                    text : '退货退款',
                    onClick : function () {
                        $this.html('退货退款');
                        var num = parseInt(content.find('.refund-num-box').data('num'));
                        content.find('input[name="num"]').val(numData.max);
                        content.find('input[name="amount"]').prev().html(amountData.max);
                        isShip.val(2);
                        content.find('.refund-num-box').removeClass('hide');
                        content.find('input[name="amount"]').prev().removeClass('hide');
                        content.find('input[name="amount"]').addClass('hide');
                    }
                }
            ];
            var btn2 = [
                {
                    text: '取消',
                    bg: 'danger'
                }
            ];
            var groups = [btn1, btn2];
            $.actions(groups);
        });
    });


    /**
     * 售后管理
     */
    $(document).on('pageInit', '#page_service_index', function (e, id, page) {
        var content = $("#" + id);
        //  确认收货
        content.find('.btn-receive-service').click(function () {
            var header  = JSON.parse(content.data('headers_cancel'));
            var no      = $(this).data('no');
            $.prompt('请输入取消理由', '取消退款', function (value) {
                if (value === '' || value === undefined) {
                    $.alert('取消理由不能为空');
                    return;
                }
                var data    = {service_no : no, remark : value};
                var params  = {
                    header : header,
                    data : data,
                    success : function (ret) {
                        $.toast(ret.msg);
                        if (ret.code === 20000) {
                            window.location.reload();
                        }
                    }
                };
                apiPost(params);
            });
        });
        //  取消售后
        content.find('.btn-cancel-service').click(function () {
            var header  = JSON.parse(content.data('headers_cancel'));
            var no      = $(this).data('no');
            $.prompt('请输入取消理由', '取消退款', function (value) {
                if (value === '' || value === undefined) {
                    $.alert('取消理由不能为空');
                    return;
                }
                var data    = {service_no : no, remark : value};
                var params  = {
                    header : header,
                    data : data,
                    success : function (ret) {
                        $.toast(ret.msg);
                        if (ret.code === 20000) {
                            window.location.reload();
                        }
                    }
                };
                apiPost(params);
            });
        });

        var methods = {
            cancelService : function (e) {
                var no = $(e.srcElement).data('no');
                var header  = getHeader(content, 'cancel');
                $.prompt('请输入取消理由', '取消售后', function (value) {
                    if (value === '' || value === undefined) {
                        $.alert('取消理由不能为空');
                        return;
                    }
                    var data    = {service_no : no, remark : value};
                    var params  = {
                        header : header,
                        data : data,
                        success : function (ret) {
                            $.toast(ret.msg);
                            if (ret.code === 20000) {
                                window.location.reload();
                            }
                        }
                    };
                    apiPost(params);
                });
            },
            receiveService : function (e) {
                var no = $(e.srcElement).data('no');
                var header  = getHeader(content, 'receive');
                $.modalPayPassword('请输入百望安全密码', '确认收货', function (value) {
                    if (value === '' || value === undefined) {
                        $.alert('安全密码不能为空');
                        return;
                    }
                    var data    = {service_no : no, pay_password : value};
                    var params  = {
                        header : header,
                        data : data,
                        success : function (ret) {
                            $.toast(ret.msg);
                            if (ret.code === 20000) {
                                window.location.reload();
                            }
                        }
                    };
                    apiPost(params);
                });
            }
        };

        //  上拉加载
        var tabs_count  = content.find('.buttons-tab a').size();
        var vm  = [];
        for (var i = 1; i <= tabs_count; i++) {
            vm[i] = vms('service-list', '#service-list', "#" + id + " #tab" + i, '/service', undefined, methods);
        }
        vm[1].request({page : 1});
        content.find('.buttons-tab a').click(function () {
            var mount = $(this).attr('href');
            var state = $(this).data('state');
            var page  = $(mount).data('page');
            var data  = {state : state, page : page}
            //  如果时第一页则请求
            if (page === 1) {
                switch (state) {
                    case 'buyer':
                        vm[2].request(data);
                        break;
                    case 'seller':
                        vm[3].request(data);
                        break;
                    case 20:
                        vm[4].request(data);
                        break;
                    case 100:
                        vm[5].request(data);
                        break;
                    default :
                        break;
                }
            }
        });

        //  上拉加载
        var loading = false;
        // 注册'infinite'事件处理函数
        $(document).on('infinite', '.infinite-scroll-bottom',function() {
            var state       = content.find('.buttons-tab .active').data('state');
            var container   = content.find('.buttons-tab .active').attr('href');
            var request     = $(container).data('request');
            var page        = parseInt($(container).data('page'));
            if (loading || request === 2) return;
            loading = true;
            var data    = {state : state, page : page};
            switch (state) {
                case 'buyer':
                    vm[2].request(data);
                    break;
                case 'seller':
                    vm[3].request(data);
                    break;
                case 20:
                    vm[4].request(data);
                    break;
                case 100:
                    vm[5].request(data);
                    break;
                default :
                    vm[1].request(data);
                    break;
            }
            loading = false;
            //容器发生改变,如果是js滚动，需要刷新滚动
            $.refreshScroller();
        });
    });

    /**
     * 售后申请
     */
    $(document).on('pageInit', '#page_service_apply', function (e, id, page) {
        var content = $("#" + id);
        content.find('.btn-submit').click(function () {
            var header  = JSON.parse(content.data('headers_header'));
            var data    = content.find('form').serialize();
            var params  = {
                header : header,
                data : data,
                success : function (ret) {
                    $.toast(ret.msg);
                    if (ret.code === 20000) {
                        $.router.load('/service');
                    }
                },
                log : true,
            }
            apiPost(params);
        });
    });

    /**
     * 售后修改
     */
    $(document).on('pageInit', '#page_service_edit', function (e, id, page) {
        var content = $("#" + id);
        content.find('.btn-submit').click(function () {
            var params  = {
                header : getHeader(content, 'headers_edit'),
                data : getData(content),
                success : function (ret) {
                    $.toast(ret.msg);
                    if (ret.code == 20000) {
                        $.router.back();
                    }
                },
            };
            apiPost(params);
        });
    });

    /**
     * 售后申诉
     */
    $(document).on('pageInit', '#page_service_appeal', function (e, id, page) {
        var content = $("#" + id);
        content.find('.btn-submit').click(function () {
            var params  = {
                header : getHeader(content, 'headers_appeal'),
                data : getData(content),
                success : function (ret) {
                    $.toast(ret.msg);
                    if (ret.code == 20000) {
                        $.router.back();
                    }
                },
            };
            apiPost(params);
        });
    });

    /**
     * 售后详情
     */
    $(document).on('pageInit', '#page_service_detail', function (e, id, page) {
        var content = $("#" + id);
        //  确认收货
        content.find('.btn-receive-service').click(function () {
            var header  = JSON.parse(content.data('headers_receive'));
            var no      = $(this).data('no');
            $.modalPayPassword('请输入安全密码', '确认收货', function (value) {
                if (value == '') {
                    $.alert('安全密码不能为空');
                    return;
                }
                var data    = {service_no : no, pay_password : value};
                var params  = {
                    header : header,
                    data : data,
                    success : function (ret) {
                        $.toast(ret.msg);
                        if (ret.code == 20000) {
                            window.location.reload();
                        }
                    }
                };
                apiPost(params);
            });
        });
        //  取消售后
        content.find('.btn-cancel-service').click(function () {
            var header  = JSON.parse(content.data('headers_cancel'));
            var no      = $(this).data('no');
            $.prompt('请输入取消理由', '取消退款', function (value) {
                if (value == '') {
                    $.alert('取消理由不能为空');
                    return;
                }
                var data    = {service_no : no, remark : value};
                var params  = {
                    header : header,
                    data : data,
                    success : function (ret) {
                        $.toast(ret.msg);
                        if (ret.code == 20000) {
                            window.location.reload();
                        }
                    }
                };
                apiPost(params);
            });
        });
    });

    /**
     * 售后邮寄商品
     */
    $(document).on('pageInit', '#page_service_ship', function (e, id, page) {
        var content = $("#" + id);
        content.find("#express-company-picker").expressCompanyPicker({
            value: ['常用', '顺丰速运']
        });
        content.find('.btn-submit').click(function () {
            var params  = {
                header : getHeader(content, 'headers_ship'),
                data : getData(content),
                success : function (ret) {
                    $.toast(ret.msg);
                    if (ret.code == 20000) {
                        $.router.load('/service/detail?service_no=' + ret.data['orders_service_no'], true);
                    }
                },
            };
            apiPost(params);
        });
    });

    /**
     * 用户关注/收藏
     */
    $(document).on('pageInit', '#page_member_attention', function (e, id, page) {
        var content = $("#" + id);
        var tabs_size   = content.find('.buttons-tab a').size();
        var vm  = [];
        var methods= {
            deleteAttentionShop : function (e) {
                var $this   = $(e.srcElement);
                var id = $this.parent().data('id');
                var params  = {
                    header : getHeader(content, 'delete_shop'),
                    data : {id : id},
                    success : function (ret) {
                        $.toast(ret.msg)
                        $this.closest('div.attention-shop-box').remove();
                    }
                };
                $.confirm('您真的要取消关注吗？', function () {
                    apiPost(params);
                });
            },
            deleteAttentionGoods : function (e) {
                var $this   = $(e.srcElement);
                var id = $this.parent().data('id');
                var params  = {
                    header : getHeader(content, 'delete_goods'),
                    data : {id : id},
                    success : function (ret) {
                        $.toast(ret.msg)
                        $this.closest('div.attention-goods-box').remove();
                    }
                };
                $.confirm('您真的要取消关注吗？', function () {
                    apiPost(params);
                });
            }
        };
        for (var i = 1; i <= tabs_size; i++) {
            var header  = content.find('.buttons-tab a').eq(i - 1).data('state');
            var template= 'attention-goods-list';
            if (header === 'shop') {
                template= 'attention-shop-list';
            }
            vm[i]   = vms(template, "#" + template, "#" + id + " #tab" + i, getHeader(content, header), undefined, methods);
        }
        vm[1].request({page : 1});
        content.find('a[href="#tab2"]').click(function () {
            var box2 = $("#tab2").find('.orders-list-box').html();
            if (box2 === null || box2 === '' || box2 === undefined) {
                vm[2].request({page : 1});
            }
        });
        //  上拉加载
        var loading = false;
        // 注册'infinite'事件处理函数
        $(document).on('infinite', '.infinite-scroll-bottom',function() {
            var state       = content.find(".buttons-tab .active").data('state');
            var container   = content.find(".buttons-tab .active").attr('href');
            var request     = $(container).data('request');
            var page        = parseInt($(container).data('page'));
            if (loading || request === 2) return;
            loading = true;
            var data    = {page : page};
            switch (state) {
                case 'goods':
                    vm[1].request(data);
                    break;
                case 'shop':
                    vm[2].request(data);
                    break;
            }
            loading = false;
            //容器发生改变,如果是js滚动，需要刷新滚动
            $.refreshScroller();
        });
    });
    /**
     * 用户优惠券
     */
    $(document).on('pageInit', '#page_member_coupon', function (e, id, page) {

    });
    /**
     * 用户账户
     */
    $(document).on('pageInit', '#page_member_account', function (e, id, page) {

    });

    /**
     * 商家首页
     */
    $(document).on('pageInit', '#page_shop_index', function (e, id, page) {
        var content = $("#" + id);
        //  关注商家
        content.find('.btn-attention-shop').click(function () {
            $.showPreloader('数据提交中...');
            var params  = {
                header : getHeader(content, 'attention'),
                data : {shop_id : $(this).data('id')},
                success : function (ret) {
                    $.hidePreloader();
                    if(ret.code = 20000){
                        $.toast('关注成功！')
                        $('.btn-attention-shop2').removeClass('hide')
                        $('.btn-attention-shop').addClass('hide')
                    }else{
                        $.toast(ret.msg)
                    }
                }
            }
            apiPost(params);
        });
        //  取消关注
        content.find('.btn-attention-shop2').click(function () {
            var params  = {
                header : getHeader(content, 'del_attention'),
                data : {shop_id : $(this).data('id')},
                success : function (ret) {
                    $.hidePreloader();
                    if(ret.code = 20000){
                        $.toast('取消关注成功！')
                        $('.btn-attention-shop').removeClass('hide')
                        $('.btn-attention-shop2').addClass('hide')
                    }else{
                        $.toast(ret.msg)
                    }
                }
            }
            $.confirm('您真的要取消关注吗？', function () {
                $.showPreloader('数据提交中...');
                apiPost(params);
            });
        });

        $(function(){  //轮播切换
            var swiper = new Swiper('.swiper-container-shop', {
                //slidesPerView: 1,
                autoplay: { //自动播放6秒
                    delay: 4000,
                    stopOnLastSlide: false,
                    disableOnInteraction: false,
                },
                //spaceBetween: 10,
                loop : true,
                //freeMode: true,
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
            });
        });
        //获取一二级分类
        content.find('.shop-category-list').click(function () {
            var shop_id = $(this).data('id');
            var sgcid = $(this).data('sgcid');
            var params  = {
                header : getHeader(content, 'headers_goods_category'),
                data : {shop_id : shop_id},
                success : function (ret) {
                    if (ret.code == 20000) {
                        var html = '';
                        for(var i = 0; i < ret.data.length; i++){
                            var cl = '';
                            if(sgcid == ret.data[i].goods_category_id){
                                cl = 'text_red';
                            }
                            html += '<a class="'+cl+'" href="/shop?id='+shop_id+'&sgcid='+ret.data[i].goods_category_id+'"><div class="rpd10 solid_b '+cl+' close-panel" data-action="'+ret.data[i].goods_category_id+'">'+ret.data[i].goods_category_name+'</div></a>';
                            if(ret.data[i].child){
                                for(var ii = 0; ii < ret.data[i].child.length; ii++){
                                    var cla = '';
                                    if(sgcid == ret.data[i].child[ii].goods_category_id){
                                        cla = 'text_red';
                                    }
                                    html += '<a class="'+cla+'" href="/shop?id='+shop_id+'&sgcid='+ret.data[i].child[ii].goods_category_id+'"><div class="rpd10 solid_b close-panel" style="margin-left: 10%;" data-action="'+ret.data[i].child[ii].goods_category_id+'">'+ret.data[i].child[ii].goods_category_name+'</div></a>';
                                }
                            }
                        }
                        $('#text_red_active').html(html);
                    }else{
                        $.toast(ret.msg);
                    }
                },
            };
            var str = $('#text_red_active').html();
            if(str.length == 0){
                apiPost(params);
            }
        });
        //商品列表
        var api_url = window.location.href;
        //  上拉加载
        var vm  = vms('goods-list', '#goods-list22', "#" + id + " #search-goods-list", api_url, 'get');
        vm.request({page : 1});
        //  上拉加载
        var loading = false;
        //注册'infinite'事件处理函数
        $(document).on('infinite', '.infinite-scroll-bottom',function() {
            var request     = content.find('#search-goods-list').data('request');
            var page        = parseInt(content.find('#search-goods-list').data('page'));
            if (loading || request === 2) return;
            loading = true;
            var data    = {page : page};
            vm.request(data);
            loading = false;
            //容器发生改变,如果是js滚动，需要刷新滚动
            $.refreshScroller();
        });
        // 添加'refresh'监听器
        $(".swiper-containerw").swiper();
        $(document).on('refresh', '.pull-to-refresh-content',function(e) {
            window.location.reload();
        });
    });

    /**
     * 查看商家营业执照
     */
    $(document).on('pageInit', '#page_shop_businesslicense', function (e, id, page) {
        var content = $("#" + id);
        //  查看商家营业执照
        content.find('.btn-attention-shop-businesslicens').click(function () {
            if($('#codes').val() == '' || $('#codes').val().length != 5){
                $.toast('验证码格式不正确！')
                return false
            }
            $.ajax({
                type: 'post',
                url: '/shop/codey',
                data: {shop_id : $('#shop_id').val(), code:$('#codes').val()},
                dataType: 'json',
                success: function (ret) {
                    if(ret.code == 20000){
                        $.toast('验证成功！')
                        $('.businesslicense').attr('src',ret.data.url)
                        $('.amg').removeClass('dn')
                        $('.shop_businesslicense').addClass('dn')
                    }else{
                        $.toast(ret.msg)
                        $('.shop_businesslicense_img').click();
                    }

                },
                error: function(e){
                        alert('请求错误，请稍后重试');
                },
            });
        });
    });

    //  买家评价首页
    $(document).on('pageInit', '#page_comment_index', function(e, id, page) {
        var content = $("#" + id);
        //  上拉加载
        var loading = false;
        // 注册'infinite'事件处理函数
        var vm  = vms('user-comment-list', '#user-comment-list', "#" + id + " #container", getHeader(content, 'comment'));
        $(document).on('infinite', '.infinite-scroll-bottom',function() {
            var request     = content.find("#container").data('request');
            var page        = parseInt(content.find("#container").data('page'));
            if (loading || request === 2) return;
            loading = true;
            var data    = {page : page};
            vm.request(data);
            loading = false;
            //容器发生改变,如果是js滚动，需要刷新滚动
            $.refreshScroller();
        });
    });
    //  买家评价追加
    $(document).on('pageInit', '#page_comment_append', function(e, id, page) {
        var content = $("#" + id);
        content.find('.btn-submit').click(function() {
            $.showPreloader();
            var data = content.find('form').serialize();
            var params = {
                header  : getHeader(content, 'append'),
                data : data,
                success : function (ret) {
                    $.hidePreloader();
                    $.toast(ret.msg);
                    if (ret.code === 20000) {
                        $.router.load('/comment', true);
                    }
                }
            }
            apiPost(params);
        });
    });
    //  买家评价修改
    $(document).on('pageInit', '#page_comment_edit', function(e, id, page) {
        var content = $("#" + id);

        var content = $("#" + id);
        content.find('.evaluation-box span').click(function () {
            content.find('.evaluation-box span').removeClass('dui_');
            $(this).addClass('dui_').closest('div').find('input').val($(this).data('id'));
        });

        content.find('.fraction-box img').click(function () {
            var parent  = $(this).closest('div');
            for(var i=1; i<=5; i++ ){
                if (i > $(this).index()) {
                    parent.find('img').eq(i-1).attr({'src':'/static/wap/images/icon/icon_love.png'});
                } else {
                    parent.find('img').eq(i-1).attr({'src':'/static/wap/images/icon/icon_love_a.png'});
                }
            }
            parent.find('input').val($(this).index());
        });

        //  匿名
        content.find('.camera_checkbox').click(function () {
            if ($(this).find('.dui').hasClass('dui_')) {
                $(this).find('.dui').removeClass('dui_')
                content.find('input[name="is_anonymous"]').val(0);
            } else {
                $(this).find('.dui').addClass('dui_')
                content.find('input[name="is_anonymous"]').val(1);
            }
        });

        //  提交数据
        content.find('.btn-submit').click(function() {
            $.showPreloader();
            var data = content.find('form').serialize();
            var params = {
                header  : getHeader(content, 'edit'),
                data : data,
                success : function (ret) {
                    $.hidePreloader();
                    $.toast(ret.msg);
                    if (ret.code === 20000) {
                        $.router.load('/comment', true);
                    }
                }
            }
            apiPost(params);
        });
    });

    // 帮助中心
    $(document).on('pageInit', '#page_help_index', function(e, id, page) {
        var content = $('#' + id);
        function requestHelpItem(that){
            if ( that.data('maxPage') == 1 ) {
                return ;
            }
            var page        = that.data('page');
            var cid         = that.data('cid');
            var pagesize    = 15;
            apiPost({
                data:{
                    page : page,
                    sid : 2,
                    cid : cid,
                    pagesize : pagesize,
                },
                async:false,
                log:true,
                header:getHeader(content, 'headers_headers0'),
                success:function(ret){
                    if ( ret.code == 20000 ) {
                        $.each(ret.data.data,function(k,v){
                            var html = '<li><a  href="/help/detail/id/' + v.article_id + '" class="fl h666 f7 pad075 text-left" style="text-overflow: ellipsis;overflow: hidden;-webkit-box-orient: vertical;display: -webkit-box;-webkit-line-clamp: 1;">' + v.article_title + '</a></li>';
                            $('.help-item').eq(that.index()).find('.infinite-scroll-preloader').first().before(html);
                        });
                        if ( ret.data.last_page <= page ) {
                            // 已经最大页数
                            that.data('maxPage',1);
                            $('.help-item').eq(that.index()).find('.infinite-scroll-preloader').first().hide();
                            // 加载完毕，则注销无限加载事件，以防不必要的加载:多个无线滚动请自行根据自己代码逻辑判断注销时机
                            // $.detachInfiniteScroll($('.infinite-scroll').eq(that.index()));
                        } else {
                            // 不是则继续下一页
                            that.data('page',page + 1);
                        }
                    } else {
                        that.data('maxPage',1);
                        $('.help-item').eq(that.index()).find('.infinite-scroll-preloader').first().hide();
                    }
                },
            });
        }
        // 初始化加载一个
        requestHelpItem($('.help-list').first());
        // 点击切换加载
        content.find('.help-list').click(function () {
            var that = $(this);
            // 显示隐藏
            that.addClass('active').siblings().removeClass('active');
            $('.help-item').addClass('hide');
            $('.help-item').eq(that.index()).removeClass('hide');
            // 加载
            if ( that.data('page') != 1 ) {
                return ;
            }
            requestHelpItem(that);
        });
        //  上拉加载
        var loading = false;
        // 注册'infinite'事件处理函数
        $(document).on('infinite',function() {
            // console.log(123);
            if (loading) {
                return;
            }
            loading = true;
            // alert(222);
            // 加载
            requestHelpItem(content.find('.help-list.active').first());
            loading = false;
            //容器发生改变,如果是js滚动，需要刷新滚动
            $.refreshScroller();
        });
    });


    //  频道首页
    $(document).on("pageInit", "#page_channel_index", function(e, id, page) {
        var content = $("#" + id);
        content.find("#search").click(function () {
            $.router.load('/search');
        });
        //首页改版
        $(function(){
            $(document).on("click", ".set_fles >a", function (e) { //频道页面选择
                $(this).addClass('active').siblings().removeClass('active')
                $(this).parents('.set_fles').find('.fix').hide()
                $(this).parents('.set_fles').find('td').removeClass('active')
                $(this).parents('.set_fles').find('td').eq($(this).index()).addClass('active')
            })
            $(document).on("click", ".set_fles .fix span, .mask_page.modal-overlay-visible", function (e) { //关闭遮罩 关闭弹层
                $('.set_fles .fix').hide()
                $('.mask_page').removeClass('modal-overlay-visible')
            })

            $(document).on("click", ".set_fles >span", function (e) { //打开遮罩 打开弹层
                $(this).siblings('.fix').show()
                $('.mask_page').addClass('modal-overlay-visible')
            })

            $(document).on("click", ".set_fles td", function (e) { //频道页面弹层内容选择
                if($(this).text()){
                    if($('.set_fles td').index(this) > Number($('.set_fles >a').size()-1) ){
                        $('.set_fles a').removeClass('active')
                    }else{
                        $('.set_fles a').eq($('.set_fles td').index(this)).addClass('active').siblings().removeClass('active')
                    }
                    $(this).parents('.fix').find('td').removeClass('active')
                    $(this).addClass('active')
                    $(this).parents('.fix').hide()
                    $('.mask_page').removeClass('modal-overlay-visible')
                }
            })
        });
        $(function(){ //轮播图
            var swiper = new Swiper('.swiper-index-banner', {
				autoplay: { //自动播放6秒
					delay: 6000,
					stopOnLastSlide: false,
					disableOnInteraction: true,
				},
                spaceBetween: 0,
                centeredSlides: true,
                loop : true,
                // spaceBetween: 10,
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
            });
        });
        $(function(){  //轮播切换
            var swiper = new Swiper('.swiper-index—nav', {
                slidesPerView: 1,
                spaceBetween: 10,
                loop : true,
                //freeMode: true,
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
            });
        });

        var vm  = [];
        var tabs_size   = content.find(".goods-list-box").size();
        var channel_name    = content.find('input[name="name"]').val();
        for (var i = 1; i < tabs_size; i++) {
            vm[i]  = vms('goods-list1', '#goods-list1', "#" + id + " #index-goods-list-" + channel_name + '-' + i, '/index/goods', 'get');
        }
        var fields  = [
            '',
            'goods_sale_num',
            'goods_attention_num',
            'goods_comment_num',
            'goods_comment_good_num',
            'goods_score_multi',
            'goods_min_price',
            // 'goods_create_time',
            'goods_shopping_score_multi',
            // 'goods_update_time',
            'goods_sku_num',
            'goods_service_days',
        ];
        var sort    = [
            'asc',
            'desc'
        ];
        var key = Math.ceil(Math.random() * 9);
        var q_order = fields[key], q_sort = sort[key % 2];
        var order_sort  = $("input[name='order']").val();
        if (order_sort !== '' && order_sort !== undefined) {
            order_sort = order_sort.split('|');
            q_order = order_sort[0];
            q_sort  = order_sort[1];
        }

        $('.swiper-index—nav a').click(function(){
            $(this).parents('.swiper-index—nav').find('a').removeClass('active');
            $(this).addClass('active');
            var i = $(this).data('i');
            var cate = $(this).data('cate');
            $('#tab_set .goods-list-box').addClass('hide').removeClass('goods-list-show');
            $("#tab_set #index-goods-list-" + channel_name + '-' + i).removeClass('hide').addClass('goods-list-show');
            var goods_list  = $("#tab_set #index-goods-list-" + channel_name + '-' + i).find('.orders-list-box').html();
            if (goods_list == '') {
                vm[i].request({page:1,cate:cate, order : q_order, sort : q_sort});
            }
        });


        var cate2 = content.find('input[name="category_id"]').val();
        vm[0]  = vms('goods-list1', '#goods-list1', "#" + id + " #index-goods-list-"+channel_name+"-0", '/index/goods', 'get');
        vm[0].request({page : 1, cate2:cate2, order : fields[key], sort : sort[key % 2]});
        //  上拉加载
        var loading = false;
        // 注册'infinite'事件处理函数
        $(document).on('infinite', '.infinite-scroll-bottom',function() {
            var active  = content.find('.swiper-index—nav a.active');
            var bottom_i    = 0;
            if (active.length > 0) {
                bottom_i    = active.data('i');
            }
            var request     = content.find('#index-goods-list-'+channel_name+'-' + bottom_i).data('request');
            var page        = parseInt(content.find('#index-goods-list-'+channel_name+'-' + bottom_i).data('page'));
            if (loading || request === 2) return;
            loading = true;
            if (bottom_i === 0) {
                var data    = {page : page, cate2:cate2, order : q_order, sort : q_sort};
            } else {
                var cate    = active.data('cate');
                var data    = {page : page, cate : cate, order : q_order, sort : q_sort};
            }
            vm[bottom_i].request(data);
            loading = false;
            //容器发生改变,如果是js滚动，需要刷新滚动
            $.refreshScroller();
        });
    });

    //  频道热卖榜
    $(document).on("pageInit", '#page_channel_hot', function (e, id, page) {
        var content = $("#" + id);
        content.find("#search").click(function () {
            $.router.load('/search');
        });
        //首页改版
        $(function(){
            $(document).on("click", ".set_fles >a", function (e) { //频道页面选择
                $(this).addClass('active').siblings().removeClass('active')
                $(this).parents('.set_fles').find('.fix').hide()
                $(this).parents('.set_fles').find('td').removeClass('active')
                $(this).parents('.set_fles').find('td').eq($(this).index()).addClass('active')
            })
            $(document).on("click", ".set_fles .fix span, .mask_page.modal-overlay-visible", function (e) { //关闭遮罩 关闭弹层
                $('.set_fles .fix').hide()
                $('.mask_page').removeClass('modal-overlay-visible')
            })

            $(document).on("click", ".set_fles >span", function (e) { //打开遮罩 打开弹层
                $(this).siblings('.fix').show()
                $('.mask_page').addClass('modal-overlay-visible')
            })

            $(document).on("click", ".set_fles td", function (e) { //频道页面弹层内容选择
                if($(this).text()){
                    if($('.set_fles td').index(this) > Number($('.set_fles >a').size()-1) ){
                        $('.set_fles a').removeClass('active')
                    }else{
                        $('.set_fles a').eq($('.set_fles td').index(this)).addClass('active').siblings().removeClass('active')
                    }
                    $(this).parents('.fix').find('td').removeClass('active')
                    $(this).addClass('active')
                    $(this).parents('.fix').hide()
                    $('.mask_page').removeClass('modal-overlay-visible')
                }
            })
        });

        content.find('.search-sort-box').find('.text_red').removeClass('text_red');
        content.find('.search-sort-box').find('.active').removeClass('active');
        var sort    = content.find('#sort').val();
        switch(sort) {
            case 'price_asc':
                var order = 'asc';
            case 'price_desc':
                if (order !== undefined) {
                    content.find('.tab-link-price').addClass('icon_tow_b').removeClass('icon_tow_a');
                } else {
                    content.find('.tab-link-price').addClass('icon_tow_a').removeClass('icon_tow_b');
                }
                content.find('.tab-link-price').addClass('active');
                break;
            case 'sales':
                content.find('.tab-link-sales').addClass('active');
                break;
            case 'shopping_score_desc':
                var order = 'desc';
            case 'shopping_score_asc':
                if (order !== undefined) {
                    content.find('.tab-link-discount-desc').addClass('text_red');
                } else {
                    content.find('.tab-link-discount-asc').addClass('text_red');
                }
                content.find('.tab-link-discount').addClass('active');
                break;
            case 'fraction':
                var order = 'fraction';
            default:
                if (order !== undefined) {
                    content.find('.tab-link-default-' + order).addClass('text_red');
                } else {
                    content.find('.tab-link-default-default').addClass('text_red');
                }
                content.find('.tab-link-default').addClass('active');
                break;
        }

        //搜索改版
        $(function(){
            $(document).on("click", ".search_set_icon a.button", function (e) { //属性选择高亮状态
                $(this).addClass('active').siblings().removeClass('active') //当前高亮状态
                $(this).siblings('.icon_tow').removeClass('icon_tow_b') //初始化价格（icon_tow）状态
                $(this).siblings().find('.ab').hide() //关闭弹窗
                $('.mask_page_bottom').removeClass('modal-overlay-visible') //关闭遮罩
                if( $(this).find('.ab').html() ){ //是否有弹窗层
                    $(document).unbind('scroll')
                    $(this).find('.ab').show()
                    $('.mask_page_bottom').addClass('modal-overlay-visible')
                }
            });

            $(document).on("click", ".mask_page_bottom", function (e) { //关闭遮罩及弹窗
                $('.search_set_icon .ab').hide()
                $('.mask_page_bottom').removeClass('modal-overlay-visible')
            });

            $(document).on("click", ".search_set_icon p", function (e) { //关闭遮罩及弹窗
                event.stopPropagation();
                $(this).addClass('text_red').siblings().removeClass('text_red')
                $('.search_set_icon .ab').hide()
                $('.mask_page_bottom').removeClass('modal-overlay-visible')
            });

            $(document).on("click", ".panel_set .search_fast", function (e) { //侧边内选择
                $('.panel_set .search_fast').removeClass('active');
                $(this).addClass('active')
            });

            $(document).on("click", ".icon_tow", function (e) { //价格选择高低状态
                $(this).toggleClass('icon_tow_b');
            });

            $(document).on("click", ".panel_set .search_fast", function (e) { //右侧弹层选项高亮选择
                $('.panel_set .search_fast').removeClass('active');
                $(this).addClass('active')
            });
        });

        $('.btn-filter').click(function () {
            var pathinfo    = content.find('input[name="pathinfo"]').val();
            pathinfo    = pathinfo.replace('.html', '');
            var from= $(this).closest('form');
            var url = from.data('url');
            var data= from.serialize();
            var params = data.split("&");
            // var host = location.search;
            var pathinfos   = pathinfo.split('/');
            //if (host !== '' && host.indexOf("?") !== -1) {
            var uri = '/channel/hot';
            var p = '';
            for (var i in params) {
                var params_arr = params[i].split('=');
                if (params_arr[1] !== '') {
                    p += '/' + params_arr[0] + '/' + params_arr[1];
                }
            }
            for (var j in pathinfos) {
                if (j % 2) {
                    continue;
                }
                var oj = parseInt(j) + 1;
                if (pathinfos[j] === 'order') {
                    uri += '/order/' + pathinfos[oj];
                } else if (pathinfos[j] !== '' && pathinfos[oj] !== '') {
                    uri += '/'+pathinfos[j]+'/' + pathinfos[oj];
                }
            }
            uri += p;
            $.router.load(uri);
        });

        content.find(".search-sort").click(function () {
            var url = $(this).data('url');
            $.router.load(url);
        });

        var vm  = vms('goods-list1', '#goods-list1', "#" + id + " #index-goods-list", '/index/goods', 'get');
        var data    = content.find('#params').val();
        if (data !== undefined && data !== '') {
            data    = JSON.parse(data);
            if (data.order === undefined) {
                data.order   = 'sales';
            }
            console.log(data)
            data.page  = 1;
            data.hot = 1;
        } else {
            var data    = {
                page : 1,
                hot : 1,
                order : 'sales'
            }
        }
        vm.request(data);
        //  上拉加载
        var loading = false;
        // 注册'infinite'事件处理函数
        $(document).on('infinite', '.infinite-scroll-bottom',function() {
            var request     = content.find('#index-goods-list').data('request');
            var page        = parseInt(content.find('#index-goods-list').data('page'));
            if (loading || request === 2) return;
            loading = true;
            var data    = content.find('#params').val();
            if (data !== undefined && data !== '') {
                data    = JSON.parse(data);
                data.page  = page;
                data.hot = 1;
                if (data.order === undefined) {
                    data.order   = 'sales';
                }
            } else {
                var data    = {
                    page : page,
                    hot : 1,
                    order : 'sales'
                }
            }
            vm.request(data);
            loading = false;
            //容器发生改变,如果是js滚动，需要刷新滚动
            $.refreshScroller();
        });
    });

    //  频道9块9
    $(document).on("pageInit", '#page_channel_nine', function (e, id, page) {
        var content = $("#" + id);
        content.find("#search").click(function () {
            $.router.load('/search');
        });
        //首页改版
        $(function(){
            $(document).on("click", ".set_fles >a", function (e) { //频道页面选择
                $(this).addClass('active').siblings().removeClass('active')
                $(this).parents('.set_fles').find('.fix').hide()
                $(this).parents('.set_fles').find('td').removeClass('active')
                $(this).parents('.set_fles').find('td').eq($(this).index()).addClass('active')
            })
            $(document).on("click", ".set_fles .fix span, .mask_page.modal-overlay-visible", function (e) { //关闭遮罩 关闭弹层
                $('.set_fles .fix').hide()
                $('.mask_page').removeClass('modal-overlay-visible')
            })

            $(document).on("click", ".set_fles >span", function (e) { //打开遮罩 打开弹层
                $(this).siblings('.fix').show()
                $('.mask_page').addClass('modal-overlay-visible')
            })

            $(document).on("click", ".set_fles td", function (e) { //频道页面弹层内容选择
                if($(this).text()){
                    if($('.set_fles td').index(this) > Number($('.set_fles >a').size()-1) ){
                        $('.set_fles a').removeClass('active')
                    }else{
                        $('.set_fles a').eq($('.set_fles td').index(this)).addClass('active').siblings().removeClass('active')
                    }
                    $(this).parents('.fix').find('td').removeClass('active')
                    $(this).addClass('active')
                    $(this).parents('.fix').hide()
                    $('.mask_page').removeClass('modal-overlay-visible')
                }
            })
        });

        content.find('.search-sort-box').find('.text_red').removeClass('text_red');
        content.find('.search-sort-box').find('.active').removeClass('active');
        var sort    = content.find('#sort').val();
        switch(sort) {
            case 'price_asc':
                var order = 'asc';
            case 'price_desc':
                if (order !== undefined) {
                    content.find('.tab-link-price').addClass('icon_tow_b').removeClass('icon_tow_a');
                } else {
                    content.find('.tab-link-price').addClass('icon_tow_a').removeClass('icon_tow_b');
                }
                content.find('.tab-link-price').addClass('active');
                break;
            case 'sales':
                content.find('.tab-link-sales').addClass('active');
                break;
            case 'shopping_score_desc':
                var order = 'desc';
            case 'shopping_score_asc':
                if (order !== undefined) {
                    content.find('.tab-link-discount-desc').addClass('text_red');
                } else {
                    content.find('.tab-link-discount-asc').addClass('text_red');
                }
                content.find('.tab-link-discount').addClass('active');
                break;
            case 'fraction':
                var order = 'fraction';
            default:
                if (order !== undefined) {
                    content.find('.tab-link-default-' + order).addClass('text_red');
                } else {
                    content.find('.tab-link-default-default').addClass('text_red');
                }
                content.find('.tab-link-default').addClass('active');
                break;
        }

        //搜索改版
        $(function(){
            $(document).on("click", ".search_set_icon a.button", function (e) { //属性选择高亮状态
                $(this).addClass('active').siblings().removeClass('active') //当前高亮状态
                $(this).siblings('.icon_tow').removeClass('icon_tow_b') //初始化价格（icon_tow）状态
                $(this).siblings().find('.ab').hide() //关闭弹窗
                $('.mask_page_bottom').removeClass('modal-overlay-visible') //关闭遮罩
                if( $(this).find('.ab').html() ){ //是否有弹窗层
                    $(document).unbind('scroll')
                    $(this).find('.ab').show()
                    $('.mask_page_bottom').addClass('modal-overlay-visible')
                }
            });

            $(document).on("click", ".mask_page_bottom", function (e) { //关闭遮罩及弹窗
                $('.search_set_icon .ab').hide()
                $('.mask_page_bottom').removeClass('modal-overlay-visible')
            });

            $(document).on("click", ".search_set_icon p", function (e) { //关闭遮罩及弹窗
                event.stopPropagation();
                $(this).addClass('text_red').siblings().removeClass('text_red')
                $('.search_set_icon .ab').hide()
                $('.mask_page_bottom').removeClass('modal-overlay-visible')
            });

            $(document).on("click", ".panel_set .search_fast", function (e) { //侧边内选择
                $('.panel_set .search_fast').removeClass('active');
                $(this).addClass('active')
            });

            $(document).on("click", ".icon_tow", function (e) { //价格选择高低状态
                $(this).toggleClass('icon_tow_b');
            });

            $(document).on("click", ".panel_set .search_fast", function (e) { //右侧弹层选项高亮选择
                $('.panel_set .search_fast').removeClass('active');
                $(this).addClass('active')
            });
        });

        $('.btn-filter').click(function () {
            var pathinfo    = content.find('input[name="pathinfo"]').val();
            pathinfo    = pathinfo.replace('.html', '');
            var from= $(this).closest('form');
            var url = from.data('url');
            var data= from.serialize();
            var params = data.split("&");
            // var host = location.search;
            var pathinfos   = pathinfo.split('/');
            //if (host !== '' && host.indexOf("?") !== -1) {
            var uri = '/channel/nine';
            var p = '';
            for (var i in params) {
                var params_arr = params[i].split('=');
                if (params_arr[1] !== '') {
                    p += '/' + params_arr[0] + '/' + params_arr[1];
                }
            }
            for (var j in pathinfos) {
                if (j % 2) {
                    continue;
                }
                var oj = parseInt(j) + 1;
                if (pathinfos[j] === 'order') {
                    uri += '/order/' + pathinfos[oj];
                } else if (pathinfos[j] !== '' && pathinfos[oj] !== '') {
                    uri += '/'+pathinfos[j]+'/' + pathinfos[oj];
                }
            }
            uri += p;
            $.router.load(uri);
        });

        content.find(".search-sort").click(function () {
            var url = $(this).data('url');
            $.router.load(url);
        });

        var vm  = vms('goods-list1', '#goods-list1', "#" + id + " #index-goods-list", '/index/goods', 'get');
        var data    = content.find('#params').val();
        if (data !== undefined && data !== '') {
            data    = JSON.parse(data);
            if (data.price_max === undefined || data.price_max > 9.90 || data.price_max <= 0) {
                data.price_max = 9.90;
            }
            data.page  = 1;
            data.free   = 1;
        } else {
            var data    = {
                page : 1,
                price_max: 9.90,
                free : 1
            }
        }
        vm.request(data);
        //  上拉加载
        var loading = false;
        // 注册'infinite'事件处理函数
        $(document).on('infinite', '.infinite-scroll-bottom',function() {
            var request     = content.find('#index-goods-list').data('request');
            var page        = parseInt(content.find('#index-goods-list').data('page'));
            if (loading || request === 2) return;
            loading = true;
            var data    = content.find('#params').val();
            if (data !== undefined && data !== '') {
                data    = JSON.parse(data);
                if (data.price_max === undefined || data.price_max > 9.90 || data.price_max <= 0) {
                    data.price_max = 9.90;
                }
                data.page  = page;
                data.free   = 1;
            } else {
                var data    = {
                    page : page,
                    price_max: 9.90,
                    free : 1
                }
            }
            vm.request(data);
            loading = false;
            //容器发生改变,如果是js滚动，需要刷新滚动
            $.refreshScroller();
        });
    });

    //  频道热每日抢购
    $(document).on("pageInit", '#page_channel_day', function (e, id, page) {
        var content = $("#" + id);
        content.find("#search").click(function () {
            $.router.load('/search');
        });
        //首页改版
        $(function(){
            $(document).on("click", ".set_fles >a", function (e) { //频道页面选择
                $(this).addClass('active').siblings().removeClass('active')
                $(this).parents('.set_fles').find('.fix').hide()
                $(this).parents('.set_fles').find('td').removeClass('active')
                $(this).parents('.set_fles').find('td').eq($(this).index()).addClass('active')
            });
            $(document).on("click", ".set_fles .fix span, .mask_page.modal-overlay-visible", function (e) { //关闭遮罩 关闭弹层
                $('.set_fles .fix').hide()
                $('.mask_page').removeClass('modal-overlay-visible')
            });

            $(document).on("click", ".set_fles >span", function (e) { //打开遮罩 打开弹层
                $(this).siblings('.fix').show()
                $('.mask_page').addClass('modal-overlay-visible')
            });

            $(document).on("click", ".set_fles td", function (e) { //频道页面弹层内容选择
                if($(this).text()){
                    if($('.set_fles td').index(this) > Number($('.set_fles >a').size()-1) ){
                        $('.set_fles a').removeClass('active')
                    }else{
                        $('.set_fles a').eq($('.set_fles td').index(this)).addClass('active').siblings().removeClass('active')
                    }
                    $(this).parents('.fix').find('td').removeClass('active')
                    $(this).addClass('active')
                    $(this).parents('.fix').hide()
                    $('.mask_page').removeClass('modal-overlay-visible')
                }
            });
        });

        content.find('.search-sort-box').find('.text_red').removeClass('text_red');
        content.find('.search-sort-box').find('.active').removeClass('active');
        var sort    = content.find('#sort').val();
        switch(sort) {
            case 'price_asc':
                var order = 'asc';
            case 'price_desc':
                if (order !== undefined) {
                    content.find('.tab-link-price').addClass('icon_tow_b').removeClass('icon_tow_a');
                } else {
                    content.find('.tab-link-price').addClass('icon_tow_a').removeClass('icon_tow_b');
                }
                content.find('.tab-link-price').addClass('active');
                break;
            case 'sales':
                content.find('.tab-link-sales').addClass('active');
                break;
            case 'shopping_score_desc':
                var order = 'desc';
            case 'shopping_score_asc':
                if (order !== undefined) {
                    content.find('.tab-link-discount-desc').addClass('text_red');
                } else {
                    content.find('.tab-link-discount-asc').addClass('text_red');
                }
                content.find('.tab-link-discount').addClass('active');
                break;
            case 'fraction':
                var order = 'fraction';
            default:
                if (order !== undefined) {
                    content.find('.tab-link-default-' + order).addClass('text_red');
                } else {
                    content.find('.tab-link-default-default').addClass('text_red');
                }
                content.find('.tab-link-default').addClass('active');
                break;
        }

        //搜索改版
        $(function(){
            $(document).on("click", ".search_set_icon a.button", function (e) { //属性选择高亮状态
                $(this).addClass('active').siblings().removeClass('active') //当前高亮状态
                $(this).siblings('.icon_tow').removeClass('icon_tow_b') //初始化价格（icon_tow）状态
                $(this).siblings().find('.ab').hide() //关闭弹窗
                $('.mask_page_bottom').removeClass('modal-overlay-visible') //关闭遮罩
                if( $(this).find('.ab').html() ){ //是否有弹窗层
                    $(document).unbind('scroll')
                    $(this).find('.ab').show()
                    $('.mask_page_bottom').addClass('modal-overlay-visible')
                }
            });

            $(document).on("click", ".mask_page_bottom", function (e) { //关闭遮罩及弹窗
                $('.search_set_icon .ab').hide()
                $('.mask_page_bottom').removeClass('modal-overlay-visible')
            });

            $(document).on("click", ".search_set_icon p", function (e) { //关闭遮罩及弹窗
                event.stopPropagation();
                $(this).addClass('text_red').siblings().removeClass('text_red')
                $('.search_set_icon .ab').hide()
                $('.mask_page_bottom').removeClass('modal-overlay-visible')
            });

            $(document).on("click", ".panel_set .search_fast", function (e) { //侧边内选择
                $('.panel_set .search_fast').removeClass('active');
                $(this).addClass('active')
            });

            $(document).on("click", ".icon_tow", function (e) { //价格选择高低状态
                $(this).toggleClass('icon_tow_b');
            });

            $(document).on("click", ".panel_set .search_fast", function (e) { //右侧弹层选项高亮选择
                $('.panel_set .search_fast').removeClass('active');
                $(this).addClass('active')
            });
        });

        $('.btn-filter').click(function () {
            var pathinfo    = content.find('input[name="pathinfo"]').val();
            pathinfo    = pathinfo.replace('.html', '');
            var from= $(this).closest('form');
            var url = from.data('url');
            var data= from.serialize();
            var params = data.split("&");
            // var host = location.search;
            var pathinfos   = pathinfo.split('/');
            //if (host !== '' && host.indexOf("?") !== -1) {
            var uri = '/channel/day';
            var p = '';
            for (var i in params) {
                var params_arr = params[i].split('=');
                if (params_arr[1] !== '') {
                    p += '/' + params_arr[0] + '/' + params_arr[1];
                }
            }
            for (var j in pathinfos) {
                if (j % 2) {
                    continue;
                }
                var oj = parseInt(j) + 1;
                if (pathinfos[j] === 'order') {
                    uri += '/order/' + pathinfos[oj];
                } else if (pathinfos[j] !== '' && pathinfos[oj] !== '') {
                    uri += '/'+pathinfos[j]+'/' + pathinfos[oj];
                }
            }
            uri += p;
            $.router.load(uri);
        });

        content.find(".search-sort").click(function () {
            var url = $(this).data('url');
            $.router.load(url);
        });

        var vm  = vms('goods-list1', '#goods-list1', "#" + id + " #index-goods-list", '/index/goods', 'get');
        var data    = content.find('#params').val();
        if (data !== undefined && data !== '') {
            data    = JSON.parse(data);
            data.page  = 1;
            data.day = 1;
            if (data.order === undefined) {
                data.order   = 'new';
            }
        } else {
            var data    = {
                page : 1,
                day : 1,
                order : 'new'
            }
        }
        vm.request(data);
        //  上拉加载
        var loading = false;
        // 注册'infinite'事件处理函数
        $(document).on('infinite', '.infinite-scroll-bottom',function() {
            var request     = content.find('#index-goods-list').data('request');
            var page        = parseInt(content.find('#index-goods-list').data('page'));
            if (loading || request === 2) return;
            loading = true;
            var data    = content.find('#params').val();
            if (data !== undefined && data !== '') {
                data    = JSON.parse(data);
                data.page  = page;
                data.day = 1;
                if (data.order === undefined) {
                    data.order   = 'new';
                }
            } else {
                var data    = {
                    page : page,
                    day : 1,
                    order : 'new'
                }
            }
            vm.request(data);
            loading = false;
            //容器发生改变,如果是js滚动，需要刷新滚动
            $.refreshScroller();
        });
    });

    //  频道热每日抢购
    $(document).on("pageInit", '#page_channel_special', function (e, id, page) {
        var content = $("#" + id);
        content.find("#search").click(function () {
            $.router.load('/search');
        });
        //首页改版
        $(function(){
            $(document).on("click", ".set_fles >a", function (e) { //频道页面选择
                $(this).addClass('active').siblings().removeClass('active')
                $(this).parents('.set_fles').find('.fix').hide()
                $(this).parents('.set_fles').find('td').removeClass('active')
                $(this).parents('.set_fles').find('td').eq($(this).index()).addClass('active')
            });
            $(document).on("click", ".set_fles .fix span, .mask_page.modal-overlay-visible", function (e) { //关闭遮罩 关闭弹层
                $('.set_fles .fix').hide()
                $('.mask_page').removeClass('modal-overlay-visible')
            });

            $(document).on("click", ".set_fles >span", function (e) { //打开遮罩 打开弹层
                $(this).siblings('.fix').show()
                $('.mask_page').addClass('modal-overlay-visible')
            });

            $(document).on("click", ".set_fles td", function (e) { //频道页面弹层内容选择
                if($(this).text()){
                    if($('.set_fles td').index(this) > Number($('.set_fles >a').size()-1) ){
                        $('.set_fles a').removeClass('active')
                    }else{
                        $('.set_fles a').eq($('.set_fles td').index(this)).addClass('active').siblings().removeClass('active')
                    }
                    $(this).parents('.fix').find('td').removeClass('active')
                    $(this).addClass('active')
                    $(this).parents('.fix').hide()
                    $('.mask_page').removeClass('modal-overlay-visible')
                }
            });
        });

        content.find('.search-sort-box').find('.text_red').removeClass('text_red');
        content.find('.search-sort-box').find('.active').removeClass('active');
        var sort    = content.find('#sort').val();
        switch(sort) {
            case 'price_asc':
                var order = 'asc';
            case 'price_desc':
                if (order !== undefined) {
                    content.find('.tab-link-price').addClass('icon_tow_b').removeClass('icon_tow_a');
                } else {
                    content.find('.tab-link-price').addClass('icon_tow_a').removeClass('icon_tow_b');
                }
                content.find('.tab-link-price').addClass('active');
                break;
            case 'sales':
                content.find('.tab-link-sales').addClass('active');
                break;
            case 'shopping_score_desc':
                var order = 'desc';
            case 'shopping_score_asc':
                if (order !== undefined) {
                    content.find('.tab-link-discount-desc').addClass('text_red');
                } else {
                    content.find('.tab-link-discount-asc').addClass('text_red');
                }
                content.find('.tab-link-discount').addClass('active');
                break;
            case 'fraction':
                var order = 'fraction';
            default:
                if (order !== undefined) {
                    content.find('.tab-link-default-' + order).addClass('text_red');
                } else {
                    content.find('.tab-link-default-default').addClass('text_red');
                }
                content.find('.tab-link-default').addClass('active');
                break;
        }

        //搜索改版
        $(function(){
            $(document).on("click", ".search_set_icon a.button", function (e) { //属性选择高亮状态
                $(this).addClass('active').siblings().removeClass('active') //当前高亮状态
                $(this).siblings('.icon_tow').removeClass('icon_tow_b') //初始化价格（icon_tow）状态
                $(this).siblings().find('.ab').hide() //关闭弹窗
                $('.mask_page_bottom').removeClass('modal-overlay-visible') //关闭遮罩
                if( $(this).find('.ab').html() ){ //是否有弹窗层
                    $(document).unbind('scroll')
                    $(this).find('.ab').show()
                    $('.mask_page_bottom').addClass('modal-overlay-visible')
                }
            });

            $(document).on("click", ".mask_page_bottom", function (e) { //关闭遮罩及弹窗
                $('.search_set_icon .ab').hide()
                $('.mask_page_bottom').removeClass('modal-overlay-visible')
            });

            $(document).on("click", ".search_set_icon p", function (e) { //关闭遮罩及弹窗
                event.stopPropagation();
                $(this).addClass('text_red').siblings().removeClass('text_red')
                $('.search_set_icon .ab').hide()
                $('.mask_page_bottom').removeClass('modal-overlay-visible')
            });

            $(document).on("click", ".panel_set .search_fast", function (e) { //侧边内选择
                $('.panel_set .search_fast').removeClass('active');
                $(this).addClass('active')
            });

            $(document).on("click", ".icon_tow", function (e) { //价格选择高低状态
                $(this).toggleClass('icon_tow_b');
            });

            $(document).on("click", ".panel_set .search_fast", function (e) { //右侧弹层选项高亮选择
                $('.panel_set .search_fast').removeClass('active');
                $(this).addClass('active')
            });
        });

        $('.btn-filter').click(function () {
            var pathinfo    = content.find('input[name="pathinfo"]').val();
            pathinfo    = pathinfo.replace('.html', '');
            var from= $(this).closest('form');
            var url = from.data('url');
            var data= from.serialize();
            var params = data.split("&");
            // var host = location.search;
            var pathinfos   = pathinfo.split('/');
            //if (host !== '' && host.indexOf("?") !== -1) {
            var uri = '/channel/special';
            var p = '';
            for (var i in params) {
                var params_arr = params[i].split('=');
                if (params_arr[1] !== '') {
                    p += '/' + params_arr[0] + '/' + params_arr[1];
                }
            }
            for (var j in pathinfos) {
                if (j % 2) {
                    continue;
                }
                var oj = parseInt(j) + 1;
                if (pathinfos[j] === 'order') {
                    uri += '/order/' + pathinfos[oj];
                } else if (pathinfos[j] !== '' && pathinfos[oj] !== '') {
                    uri += '/'+pathinfos[j]+'/' + pathinfos[oj];
                }
            }
            uri += p;
            $.router.load(uri);
        });

        content.find(".search-sort").click(function () {
            var url = $(this).data('url');
            window.location.href = url;
            return;
            $.router.load(url);
        });

        var vm  = vms('goods-list1', '#goods-list1', "#" + id + " #index-goods-list", '/index/goods', 'get');
        var data    = content.find('#params').val();
        if (data !== undefined && data !== '') {
            data    = JSON.parse(data);
            data.page  = 1;
        } else {
            var data    = {
                page : 1,
            }
        }
        vm.request(data);
        //  上拉加载
        var loading = false;
        // 注册'infinite'事件处理函数
        $(document).on('infinite', '.infinite-scroll-bottom',function() {
            var request     = content.find('#index-goods-list').data('request');
            var page        = parseInt(content.find('#index-goods-list').data('page'));
            if (loading || request === 2) return;
            loading = true;
            var data    = content.find('#params').val();
            if (data !== undefined && data !== '') {
                data    = JSON.parse(data);
                data.page  = page;
            } else {
                var data    = {
                    page : page,
                }
            }
            vm.request(data);
            loading = false;
            //容器发生改变,如果是js滚动，需要刷新滚动
            $.refreshScroller();
        });
    });

    $.init();
});