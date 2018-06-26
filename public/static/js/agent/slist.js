// JavaScript Document
$(function(){
	//列表下拉
	$('.slist_title').on('click',function(){
		if($(this).parent().hasClass('slist_')){
				$(this).parent().removeClass('slist_')
				}else{
					$(this).parent().addClass('slist_')
					}	
		})

	$(document).on('click','.slist li',function(){
		$(this).parent().parent().removeClass('slist_').find('.slist_title').text($(this).text())
		})
	//按钮
	$('.no_dui').click(function(){
				$(this).parent().find('.no_dui').removeClass('dui');
				$(this).addClass('dui')
		})

	//按钮点击切换
	$('.tog').hide();
	$('.tog').eq(0).show();
	$('.tog_dui .no_dui').click(function(){
		var n=$('.no_dui').index(this);
				$(this).parent().find('.no_dui').removeClass('dui');
				$(this).addClass('dui')
				$('.tog').hide().eq(n).show();
		})
	
	
	//验证码
	var shi=60;
	$('.yz').click(function(){
		if(shi==60){
			var time = setInterval(function(){
				shi-=1;
				$('.yz').text(shi+'S后重新获取').css('background','#D6D6D6')
				if(shi==0){
					shi=60;
					clearInterval(time)
					$('.yz').text('获取验证码').css('background','#40abf3')
					}
				},1000)
		}
	})
	
	

})