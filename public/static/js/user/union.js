// JavaScript Document
$(function(){
var time_=15;	
var time=setInterval(function(){
		if(time_!=0){
			time_-=1;
			$('.hui_btn').html('我已阅读并同意该协议（'+time_+'S）')
			}else{
				clearInterval(time)
				$('.hui_btn').addClass('button').html('我已阅读并同意该协议')
				$('.hui_btn').attr('href','verification')
				}
	
	},1000)	
	
	
	$('.left_list li').hover(function(){
	$(this).find('.list_dd').show();
})

$('.list_dd dd').hover(function(){
	$(this).find('.list_3').show();
	$('.list_dd').show();
},function(){
	$(this).find('.list_3').hide();
	$('.list_dd').show();
	})
	
	
})