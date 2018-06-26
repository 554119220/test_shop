$(function(){

	var lod=0;
	var timer=setInterval(function(){
		$('.hloding .banner a').removeClass('show')
		$('.hloding .banner a').eq(lod).fadeOut(600);
		if(lod==4){
			lod=0;
		}else{
			lod+=1;
		}
		$('.hloding .banner a').eq(lod).fadeIn(800)
	},3000);
})
