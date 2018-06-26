// JavaScript Document
$(function(){

//user 主页头部滚动事件
//$(document).scroll(function(){    
//	console.log('sss')
//    viewH =$(this).height();//可见高度  
//       contentH =$(this).get(0).scrollHeight;//内容高度  
//       scrollTop =$(this).scrollTop();//滚动高度  
//			if(scrollTop >= 50) { 
//		//滚动高度大于等于200的时候执行
//		alert("p")
//			}
//
// });	
$(".content.s").on('scroll',function(){
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

	
	
	
	
	
//编辑地址
	$('.bj .dizhi').click(function(){
		if($(this).hasClass('dizhi1')){
			$(this).removeClass('dizhi1');
		}else{
			$(this).addClass('dizhi1');
		}
		
	})
	
	//地区选择
	
	$('.qiye_list li').click(function(){
		$(this).parent().find('li').removeClass('cred')
		$(this).addClass('cred')
		var n=$('.qiye_list li').index(this);
		$(this).parent().parent().find('.qiye_list1').hide();
		$('.qiye_list1').eq(n).show();
	})
	
	$('.qiye_list1 li').click(function(){
		$(this).parent().find('li').removeClass('cred')
		$(this).addClass('cred')
	})
	
		/*点击分类*/
	$('.tog').click(function(){
		var n=$('.tog').index(this);
		$('.f_back').eq(n).show()
	})
//	/点击关闭/
	$('.qiye_title i').click(function(){
		var n=$('.qiye_title i').index(this);
		$('.f_back').eq(n).slideUp();
	})
	




//购物车
//	标题编辑
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
//	/产品编辑/
	$(document).on('click','.cart_cpx strong',function(){
			$(this).parent().hide();
			$(this).parent().parent().find('.delbtn').show()
		})
	$(document).on('click','.cart_newcp .go',function(){
			$(this).parent().hide();
			$(this).parent().parent().find('.cart_cpx').show()
		})
		
	// 勾选	计算
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
				$('.dui').addClass('dui_');
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
	
	
	
	
//收银台
	$(document).on('click','.shou_fs li',function(){
		$('.shou_fs li').removeClass('shou_dui');
		$(this).addClass('shou_dui')
		
	})
	//支付失败
	$(document).on('click','.btnh',function(){
		$('.f_back').show()
		
	})
	//支付失败 关闭
	$(document).on('click','.shibai .red-b',function(){
		$('.f_back').hide()
		
	})

//添加收货地址
		$(document).on('click','.del',function(){
			$(this).parent().parent().remove();

					
		})
		
		
		//店铺勾选
		$(document).on('click','.append_zd .dui',function(){
			$('.dui').removeClass('dui_');
			$(this).addClass('dui_');
		})
		

		
//评价晒单
	$(document).on('click','.camera_checkbox .dui',function(){
	    $(this).toggleClass('dui_')
	})
	
	$(document).on('click','.camera_img span',function(){
	    $(this).parents('.camera_img').remove()
	})
	
	$(document).on('click','#score_number img',function(){	
	    $(this).siblings().attr({'src':'../images/icon/ic_star.png'});
	    for(i=0; i<=$(this).index(); i++ ){
	        $('#score_number img').eq(i).attr({'src':'../images/icon/ic_star_a.png'});
	    }
	})


//登录
$(document).on('click','.lbot',function(){
		if($(this).hasClass('lbot1')){
			$(this).removeClass('lbot1');
			$('.lji').hide();
		}else{
			$(this).addClass('lbot1');
			$('.lji').show()
		}
		
	})
	//点击密码
	$(document).on('click','.lkai',function(){
		if($(this).hasClass('lkai1')){
			$(this).removeClass('lkai1');
			$('.lmi').prop('type', 'password')
		}else{
			$(this).addClass('lkai1');
			$('.lmi').prop('type', 'text')
		}
		
	})
	//选择记录的帐号
	$(document).on('click','.lji span',function(){
		$('.luser').val($(this).text())
		$('.lji').hide();
		$('.lbot').removeClass('lbot1');
	})
	//删除记录的账号
	$(document).on('click','.lji i',function(){
		$(this).parent().remove();
	})



//个人注册
//协议打勾
	$(document).on('click','.xieyi',function(){
		if($(this).hasClass('xieyi-dui')){
			$(this).removeClass('xieyi-dui');

		}else{
			$(this).addClass('xieyi-dui');

		}
		
	})

//企业注册
//选择类型
	$(document).on('click','.q_no',function(){
		$('.qiye_tog').slideUp()
		
	})
	$(document).on('click','.wbor.s',function(){
		$('.qiye_tog').slideDown()
		
	})
	//选中类型
	$(document).on('click','.qiye_tog li',function(){
		$('.qiye_tog li').removeClass('hot')
		$(this).addClass('hot')
		$('.wbor.s input').val($(this).text())
	})
	
	

	//验证码
	var shi=60;
	$(document).on('click','.yan',function(){
		if(shi==60){
			var time = setInterval(function(){
				shi-=1;
				$('.yan').text('获取验证码('+shi+')').css('background','#D6D6D6')
				if(shi==0){
					shi=60;
					clearInterval(time)
					$('.yan').text('获取验证码').css('background','#40abf3')
					}
				},1000)
		}

		
		
	})


//分类
$(function(){
	$(document).on('click','.flist li',function(){
		var n=$('.flist li').index(this);
		$('.flist li').removeClass('hot');
		$(this).addClass('hot');
		$('.fcp').hide();
		$('.fcp').eq(n).show();
	})
	
	
})	



//积分 现金明细
	$(document).on('click','.bott',function(){
		var n=$('.bott').index(this);
		if($(this).hasClass('top_red')){
			$('.bott').removeClass('top_red')
			$('.xj_opa').hide();
		}else{
			$('.bott').removeClass('top_red')
			$(this).addClass('top_red')
			$('.xj_opa').hide().eq(n).show();
		}
		
	})
	$(document).on('click','.list2rem.bbai li',function(){
		$('.xj_opa').hide()
		$('.bott').removeClass('top_red')
	})






//企业用户认证
$(document).on('click','.qiye_btn .dui',function(){
		$(this).parent().find('.dui').removeClass('dui_');
		$(this).addClass('dui_')
	})
	
	/*点击行业分类*/
	$(document).on('click','.s.hot',function(){
		var n=$('.s.hot').index(this);
		$('.f_back').eq(n).show()
	})
	
//	/点击关闭/
	$(document).on('click','.qiye_title i',function(){
			var n=$('.qiye_title i').index(this);
			$('.f_back').eq(n).slideUp();
		})

//



//提现
$(document).on('click','.ti_tog .s',function(){	
			$('.ti_tog .s').removeClass('bor')
			$(this).addClass('bor')
		
		
	})




//添加银行卡

	//	/点击关闭/
	$(document).on('click','.f_back .rright',function(){
			var n=$('.f_back .rright').index(this);
			$('.f_back').eq(n).slideUp();
		})
		//银行卡选择
		$(document).on('click','.bank_main .list2rem li',function(){	
			$(this).parent().find('li').removeClass('new');
			$(this).addClass('new')
			$(this).parent().parent().parent().parent().find('.hot').text($(this).text());
		})

//联系商家
	$(document).on('click','.shop-about',function(){	
			$('.f_back').show()	
	})
	
	$(document).on('click','.btn-ko',function(){	
			$('.f_back').hide()	
	})

//商品详情按钮
	$(document).on('click','.btn-cp-img',function(){
			$('.btn-cp-data').removeClass('text_red')
			$('.btn-cp-img').addClass('text_red')
			$('.cp-data').hide()	
			$('.cp-data-img').show()	
	})
	
	$(document).on('click','.btn-cp-data',function(){
			$('.btn-cp-img').removeClass('text_red')
			$('.btn-cp-data').addClass('text_red')
			$('.cp-data-img').hide()	
			$('.cp-data').show()	
	})

//帮助中心
	$(document).on('click','.help-btn span',function(){	
			var n=$('.help-btn span').index(this);
			$('.help-btn span').removeClass('active');
			$(this).addClass('active');
			$('.list2rem').hide();
			$('.list2rem').eq(n).show();
	})
	

	
//	发货快递
	$("#express").picker({
	  toolbarTemplate: '<header class="bar bar-nav bbai">\
	  <button class="button button-link pull-right close-picker">确定</button>\
	  <h1 class="title">配送方式</h1>\
	  </header>',
	  cols: [
	    {
	      textAlign: 'center',
	      values: [ '顺丰(免邮)', '圆通', 'iPad 2', 'iPad Retina', 'iPad Air', 'iPad mini']
	    }
	  ]
	});

$("#bank").picker({
  toolbarTemplate: '<header class="bar bar-nav bankback">\
  <button class="button button-link pull-right close-picker">确定</button>\
  </header>',
  cols: [
    {
      textAlign: 'center',
      values: ['T+0 0个工作日 手续费7%', 'T+1 1个工作日 手续费5%', 'T+3 3个工作日 手续费3%', 'T+7 7个工作日 手续费1%']
    }
  ]
});






		






})//load end
