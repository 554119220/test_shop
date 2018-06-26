// JavaScript Document
$(function(){

	$('.no_dui').click(function(){
				$(this).parent().find('.no_dui').removeClass('dui');
				$(this).addClass('dui')
		})

	//点击切换
	$('.tog').hide();
	$('.tog').eq(0).show();
	$('.tog_dui .no_dui').click(function(){
		var n=$('.no_dui').index(this);
				$(this).parent().find('.no_dui').removeClass('dui');
				$(this).addClass('dui')
				$('.tog').hide().eq(n).show();
		})

	












})