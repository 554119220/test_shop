//头部滚动
$(document).scroll(function(){
	viewH =$(this).height();//可见高度
	contentH =$(this).get(0).scrollHeight;//内容高度
	scrollTop =$(this).scrollTop();//滚动高度
	if(scrollTop >= 500) {
		//滚动高度大于等于500的时候执行
		$(".head").addClass('head_f')
		$(".headtop").addClass('headtop1')
		$('body').css({'padding-top':'106px'})
	}else{
		$(".head").removeClass('head_f')
		$(".headtop").removeClass('headtop1')
		$('body').css({'padding-top':'0px'})
	}

});
//头部list
$(document).on('click','.head_ul .s2 a',function(){
	var n=$('.head_ul .s2 a').index(this);
	$('.nav_centent .centent_page').hide().eq(n).show();
})

//头部-业务中心
$(document).on('mouseover','.header .nav a',function(){
	var n=$('.header .nav a').index(this);
	$('.head_ul li').hide().eq(n).show();
})

$(document).on('click','.head_ul h3',function(){
	$(this).parent().hide()
})
$(document).on('mouseover','.main100',function(){
	$('.head_ul li').hide()
})

////业务中心nav
//$(document).on('mouseover','.nav_title .plr5',function(){
//	var n=$('.nav_title .plr5').index(this);
//	$('.nav_title .active').removeClass('active')
//	$(this).find('div').addClass('active');
//	$('.nav_centent .centent_page').hide().eq(n).show();
//	$('.business_img').css({'background-image':'url(/images/index/ss'+n+'.jpg)'})
//})

//关于百望 nav
$(document).on('click','.about_nav .nav_title li',function(){
	var n=$('.about_nav .nav_title li').index(this);
	$('.about_nav .nav_title li').removeClass('active')
	$(this).addClass('active');
	$('.about_nav .nav_page').hide().eq(n).show();
})

//首页轮播图
$(document).on('click','.w_left',function(){
	var n=parseInt($(this).parent().attr('b'));
	if(n==0){
		n=$(this).parent().find('li img').length-1;
	}else{
		n-=1;
	}
	$(this).parent().attr('b',n);
	$(this).parent().find('li').fadeOut().eq(n).fadeIn();
	$(this).parent().find('dd').removeClass('dd').eq(n).addClass('dd');
})
$(document).on('click','.w_right',function(){
	var n=parseInt($(this).parent().attr('b'));
	if(n<$(this).parent().find('li img').length-1){
		n+=1;
	}else{
		n=0;
	}
	$(this).parent().attr('b',n);
	$(this).parent().find('li').fadeOut().eq(n).fadeIn();
	$(this).parent().find('dd').removeClass('dd').eq(n).addClass('dd');
})


//新闻中心nav切换
$(document).on('click','.news .nav a',function(){
	$('.news .nav a').removeClass('nav_a');
	$(this).addClass('nav_a')
})


//帮助中心
//列表显示
$(document).on('click','.help_left h3',function(){
	$(this).parent().find('.nav').toggle('fast')
	if($(this).find('i').hasClass('i')){
		$(this).find('i').removeClass('i');
	}else{
		$(this).find('i').addClass('i');
	}
})
$(document).on('click','.help_left .nav a',function(){
	$('.help_left .nav a').removeClass('red1');
	$(this).addClass('red1');
})