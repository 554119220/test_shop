// JavaScript Document
$(function(){
	//点击对效果
	$('.content').hide();
	$('.content').eq(0).show();
	$('.reg_title a').click(function(){
		var n=$('.reg_title a').index(this);
		$('.reg_title a').removeClass('reg_title1');
		$(this).addClass('reg_title1');
		$('.content').hide();
		$('.content').eq(n).show();
		})
	//点击勾不勾选	
	$('.reg_xx span').click(function(){
			if($(this).hasClass('dui')){
				$(this).removeClass('dui')
				}else{
					$(this).addClass('dui')
					}
		})

	//显示密码
	$('.mi_').click(function(){
		if($(".mima").attr("type") == "password"){
			$(".mima").attr("type","text");
			}else{
				$(".mima").attr("type","password");
				}
		})

	//验证码
	var shi=60;
	$('.yan').click(function(){
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
	//验证
	var Yname = /^[\u4e00-\u9fa5]{2,4}$/;	//验证姓名2~4中文
	var Ytel = /^1[3,4,5,8]\d{9}$/;	//验证手机
    var Y020=/\d{3,4}-\d{7,8}/;	//验证座机
	var Ymail=/^[a-z0-9]+([._\\-]*[a-z0-9])*@([a-z0-9]+[-a-z0-9]*[a-z0-9]+.){1,63}[a-z0-9]+$/;//邮箱验证
	var Ymi=/^[a-zA-Z+\d+]{6,}$/; //不用低于6位密码验证	
	var Ymi_= /^(?![^a-zA-Z]+$)(?!\D+$).{6,10}/; //不用低于6位密码 英文+数字组

	//登录页
	$('#login_btn').click(function(){
		 if($('.user').val()==''){
			 alert('请输入用户名')
			 return false
			 }
		 if($('.mima').val()==''||$('.mima').val().length<6){
			 alert('请输入正确的密码')
			 return false
			 }
		 if($('#yan').val()==''){
			 alert('请输入验证码')
			 return false
			 }
		
	})

	//忘记找回密码
	$('#wang_btn1').click(function(){
		if($('.name').val()==''){
			 alert('请输入用户名')
			 return false
			 }
		 if($('.tel').val()=='' || !Ytel.test($('.tel').val())){
			 alert('请输入正确的手机号')
			 return false
			 }
		 if($('.yan_input').val()==''){
			 alert('请输入验证码')
			 return false
			 }	
	})
	
	$('#wang_btn2').click(function(){
		if($('.name1').val()==''){
			 alert('请输入用户名')
			 return false
			 }
		 if($('.mail').val()=='' || !Ymail.test($('.mail').val())){
			 alert('请输入正确的邮箱号')
			 return false
			 }
		 if($('.yan_input1').val()==''){
			 alert('请输入验证码')
			 return false
			 }	
	})

	//个人注册
	$('.btn_main .btn1').eq(0).click(function(){
		if($('.r_input').eq(0).val()==''){
			 alert('请输入用户名')
			 return false
			 }
		 if($('.r_input').eq(1).val()=='' || !Ytel.test($('.r_input').eq(1).val())){
			 alert('请输入正确的手机号')
			 return false
			 }
		 if($('.r_input').eq(2).val()==''){
			 alert('请输入验证码')
			 return false
			 }	
		if($('.r_input').eq(3).val()==''||$('.r_input').eq(3).val().length<6){
			 alert('请输入正确的密码')
			 return false
			 }	
		if($('.r_input').eq(3).val()!=$('.r_input').eq(4).val()){
			 alert('两次密码不一致')
			 return false
			 }	 
			 
			 
	})

	$('.btn_main .btn1').eq(1).click(function(){
		if($('.reg_name').val()==''){
			 alert('请输入用户名')
			 return false
			 }
		 if($('.reg_gs').val()==''){
			 alert('请填写公司名')
			 return false
			 }
		if($('.reg_mi').val()==''||$('.reg_mi').val().length<6){
			 alert('请输入正确的密码')
			 return false
			 }	
		if($('.reg_mi').val()!=$('.reg_mi1').val()){
			 alert('两次密码不一致')
			 return false
			 }	 
		if($('.tel').eq(1).val()=='' || !Ytel.test($('.tel').eq(1).val())){
			 alert('请输入正确的手机号')
			 return false
			 }	 
			 
	})

	//密码重置
	$('.mi_bot .login_btn').click(function(){
		if($('.r_input').eq(0).val()==''||$('.r_input').eq(0).val().length<6){
			 alert('请输入正确的密码')
			 return false
			 }	
		if($('.r_input').eq(0).val()!=$('.r_input').eq(1).val()){
			 alert('两次密码不一致')
			 return false
			 }	
		
	})









	//第三方js
	$('.title_xq').click(function(){
	$('.title_hidden').toggle('slow')
	})
	
	$('.fu_logo_main li').click(function(){
		$('.fu_logo_main li').removeClass('fu_dui');
		$(this).addClass('fu_dui')
		})	

	<!--left导航显示对应-->
	$('.main_s').eq(0).show();
	$('.left_3 li').click(function(){
			$('.left_3 li').removeClass('a')
			$(this).addClass('a');
			var n=$('.left_3 li').index(this);
			$('.main_s').hide();
			$('.main_s').eq(n).show();
		})








})