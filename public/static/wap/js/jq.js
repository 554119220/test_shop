//左边list
$(function(){
	$(document).on('click','.list-btn',function(){
		var n=$('.list-btn').index(this);
				console.log('asd')
		if($(this).hasClass('list-btn1')){
			$(this).removeClass('list-btn1')
			$('.list-list').eq(n).hide();
		}else{
			$(this).addClass('list-btn1')
			$('.list-list').eq(n).show();
			
		}
	})

	//发布商品
	$(document).on('click','.nav-ul li a',function(){
		$(this).parent().parent().find('a').removeClass('bg-gray');
		$(this).addClass('bg-gray')
	})


	//全选
	$(document).on('click','#btn-active',function(){
		var n=$("tbody input[type='checkbox']");
		if($("#btn-active").is(":checked")){
				for(var i=0;i<n.length;i++){
					n.get(i).checked='checked';
				}
			
			}else{
				for(var i=0;i<n.length;i++){
					n.get(i).checked=false;
				}
			}
	})

	//搜索btn
	$(document).on('click','#search-btn',function(){
		if($('#search-main').hasClass('hide')){
			$('#search-main').removeClass('hide')
		}else{
			$('#search-main').addClass('hide')
			
		}
	})

	
	//分类管理
	$(document).on('click', '.append-btn', function() {
		var n = $('.append-btn').index(this);
		$('.append-btn').eq(n).parent().parent().before('<tr><td><label class="square mar0 fl"><input type="checkbox"><em class="fl"></em></label></td><td><div class="btn-group bootstrap-select bs-select form-control w300 fl fs12 pd0 select-radius center"><button type="button" class="btn dropdown-toggle btn-default" data-toggle="dropdown" title="顺丰快递"><span class="filter-option pull-left">顺丰快递</span>&nbsp;<span class="bs-caret"><span class="caret"></span></span></button><div class="dropdown-menu open"><ul class="dropdown-menu inner" role="menu"><li data-original-index="0" class="selected"><a tabindex="0" class="" style="" data-tokens="null"><span class="text">顺丰快递</span><span class="fa fa-check check-mark"></span></a></li><li data-original-index="1"><a tabindex="0" class="" style="" data-tokens="null"><span class="text">Ketchup</span><span class="fa fa-check check-mark"></span></a></li><li data-original-index="2"><a tabindex="0" class="" style="" data-tokens="null"><span class="text">Relish</span><span class="fa fa-check check-mark"></span></a></li></ul></div><select class="bs-select form-control w300 fl fs12 pd0 select-radius center" tabindex="-98"><option>顺丰快递</option><option>Ketchup</option><option>Relish</option></select></div></td><td><div class="btn-group bootstrap-select bs-select form-control w300 fl fs12 pd0 select-radius center"><button type="button" class="btn dropdown-toggle btn-default" data-toggle="dropdown" title="顺丰快递"><span class="filter-option pull-left">顺丰快递</span>&nbsp;<span class="bs-caret"><span class="caret"></span></span></button><div class="dropdown-menu open"><ul class="dropdown-menu inner" role="menu"><li data-original-index="0" class="selected"><a tabindex="0" class="" style="" data-tokens="null"><span class="text">顺丰快递</span><span class="fa fa-check check-mark"></span></a></li><li data-original-index="1"><a tabindex="0" class="" style="" data-tokens="null"><span class="text">Ketchup</span><span class="fa fa-check check-mark"></span></a></li><li data-original-index="2"><a tabindex="0" class="" style="" data-tokens="null"><span class="text">Relish</span><span class="fa fa-check check-mark"></span></a></li></ul></div><select class="bs-select form-control w300 fl fs12 pd0 select-radius center" tabindex="-98"><option>顺丰快递</option><option>Ketchup</option><option>Relish</option></select></div></td><td><div>2017-06-10</div><div>12：59：53</div></td><td><a class="color-blue mr5" href="">查看</a><a class="color-blue mr5" href="">删除</a><a class="color-blue" href="">已修改</a></td></tr>');
	})


	//选择经验类型
	$(document).on('click','.class-active li',function(){
		$(this).parent().find('li').removeClass('active');
		$(this).parent().attr('title',$(this).text())
		$(this).addClass('active')
		var n=$(this).parent().attr('id');
		if(n=='list'){
			$('.class-active').eq(1).attr('title','')
			$('.class-active').eq(1).find('li').removeClass('active');
		}
	})
	$(document).on('click','.fs12.red-action',function(){
		var n=$('.class-tog').index(this);
		$('.bg-000-f').eq(n).show()
	})
	$(document).on('click','.btn-x',function(){
		var n=$('.btn-x').index(this);
		$('.bg-000-f').eq(n).hide()
	})
	//确定
	$(document).on('click','.class-btn',function(){
		var n=$('.class-btn').index(this);
		$('.bg-000-f').hide()
		$('.class-text').eq(n).val($('.class-active').eq(0).attr('title')+' > '+$('.class-active').eq(1).attr('title'))
	})


//商品管理tab切换
	$(document).on('click','.nav-tog a',function(){
		var n=$('.nav-tog a').index(this);
		$('.nav-tog a').removeClass('cp-active')
		$(this).addClass('cp-active')
		$('.nav-tog-list').hide();
		$('.nav-tog-list').eq(n).show();
	})
	//添加分组
	$(document).on('click','#append',function(){
		$('#append-main').append('<div class="main100 dashed-bot pb10 over mb10"><div class=" bor-colorf5 over radius4px fl mr15"><a class="btn color666 bg-gray  fl text-center w80 ">分组名称</a><input class=" line32 bor_no pl10 w200" placeholder="请输入分组名称"></div><div class=" bor-colorf5 over radius4px fl mr15"><a class="btn color666 bg-gray  fl text-center w80 ">分组排序</a><input class=" line32 bor_no pl10 w200" placeholder="请输入分组排序"></div><a class="btn text_white bg-red-thunderbird radius4px pd8 pl15 pr15 mr10 fs12">选商品</a><a class="btn text_white bg-red-thunderbird radius4px pd8 pl15 pr15 mr10 fs12 remove-btn">移除分组</a></div>')
	})
	//删除分组
	$(document).on('click','.remove-btn',function(){
		$(this).parent().remove();
	})
	



//登录头部切换
	$('.content').hide();
	$('.content').eq(0).show();
	$('.reg_title a').click(function(){
		var n=$('.reg_title a').index(this);
		$('.reg_title a').removeClass('reg_title1');
		$(this).addClass('reg_title1');
		$('.content').hide();
		$('.content').eq(n).show();
		})

	//显示密码
	$('.mi_').click(function(){
		if($(".mima").attr("type") == "password"){
			$(".mima").attr("type","text");
			}else{
				$(".mima").attr("type","password");
				}
		})


//入驻要求
$(document).on('click','.shop-btn a',function(){
		$('.shop-btn a').removeClass('cp-active');
		$(this).addClass('cp-active')
		var n=$('.shop-btn a').index(this);
		$('.shop-text').hide().eq(n).show()
	})

	

//end
})
