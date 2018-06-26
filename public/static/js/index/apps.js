$(document).ready(function(){

});

/**
 * 创建模态框
 * @param header 	是否显示头部标题栏 show|hide
 * @param footer 	是否显示底部 show|hide
 * @param width  	宽度
 * @param msg	 	显示内容
 * @param url 	 	加载页面内容
 */
function vmodal(options,callback){
	if(options == undefined) var options={};
	var params = {};
	var tag = options.tag ? options.tag : '.ajax-modal';
	
	if(options.header && options.header == 'hide') $(tag).find('.modal-header').addClass('hide');
	else $(tag).find('.modal-header').removeClass('hide');	
	
	if(options.footer && options.footer == 'show') $(tag).find('.modal-footer').removeClass('hide');
	else $(tag).find('.modal-footer').addClass('hide');
	
	if(options.footerBtn){
		$(tag).find('.modal-footer .btns').html(options.footerBtn);
	}else{
		$(tag).find('.modal-footer .btns').html('');
	}
	
	if(options.title) $(tag).find('.modal-title').html(options.title);
	if(options.width) params.width = options.width;
	
	if(options.msg) $(tag).find('.modal-body').html(options.msg);
	else if(options.url) {
		$(tag).find('.modal-body').html('<div class="text-center"><img src="/images/loading.gif" alt="loading"></div>').load(options.url);
	}
	
	$(tag).modal(params);
	
	if(callback) callback();
}

/**
 * 获取表单数据
 */
function getFormJson(form) {
	var o = {};
	var a = $(form).serializeArray();
	$.each(a, function () {
		if (o[this.name] !== undefined) {
			if (!o[this.name].push) {
				o[this.name] = [o[this.name]];
			}
			o[this.name].push(this.value || '');
		} else {
			o[this.name] = this.value || '';
		}
	});
	return o;
}

/**
 * 表单提交
 */
function submitForm(params){	
	var formid	= $(params.formid);
	var data	= formid.data();
	var url		= formid.attr('action');
	if(params.url != undefined && params.url) url = params.url;

	var options = {
		beforeSubmit:'',
		target: '#ajax_form',
		url: url,
		type: 'POST',
		dataType:'json',
		success:function(ret){
			console.log(ret);
			if(params.success) params.success(ret);
		},
		error:function(e){
			alert('请求失败！');
		}
	};
	
	//console.log(options);
	formid.ajaxSubmit(options);
	return false;
}

function ajax_post(params){
	$.ajax({  
		type: 'post', 
		url: params.url,
		data:params.data,
		dataType:'json',
		async: params.async ? true : false,
		success: function (ret) {
			//console.log(ret);
			if(params.success) params.success(ret);
		},
		error:function(e){
			alert('请求失败！');
		}
	});
	return false;
}

function ajax_get(params){
	$.ajax({  
		type: 'get', 
		url: params.url,
		dataType:'json',
		async: params.async ? true : false,
		success: function (ret) {
			//console.log(ret);
			if(params.success) params.success(ret);
		},
		error:function(e){
			alert('请求失败！');
		}
	});
	return false;
}

/**
 * 提示通知
 */
function toast(params){
	var code = params.code ? params.code : 0;
	var msg  = params.msg ? params.msg : '提示内容！';
	var title = params.title ? params.title : '提示信息';
	toastr.options = {
	  "closeButton": true,
	  "debug": false,
	  "positionClass": "toast-bottom-center",
	  "onclick": null,
	  "showDuration": "1000",
	  "hideDuration": "1000",
	  "timeOut": "5000",
	  "extendedTimeOut": "1000",
	  "showEasing": "swing",
	  "hideEasing": "linear",
	  "showMethod": "fadeIn",
	  "hideMethod": "fadeOut"
	};
	
	switch(code){
		case 1:
			toastr.success(msg,title);
		break;
		case 2:
			toastr.warning(msg,title);
		break;
		case 0:
			toastr.error(msg,title);
		break;
		case 3:
			toastr.info(msg,title);
		break;
	}	
}


/**
 * 全选/反选
 */
function selectAll(params){
	var tag = params.tag ? params.tag : 'body';
	
	//var size = $(tag).find('input[type="checkbox"]').size();

	$(tag).find('input[type="checkbox"]').each(function(index){		
		if($(this).is(':checked') == true) $(this).prop('checked',false);
		else{
			$(this).prop('checked',true);
		}
	});

}


//跳至某页
function gopage(url,obj){
	var p = obj.closest('.input-group').find('#custom_goto_page').val();
	var pagesize = obj.closest('.input-group').find('#custom_pagesize').val();
	if(!pagesize) pagesize = 20;
	
	url += '/p/'+p+'/pagesize/'+pagesize;
	location.href = url;
}


function vmodal_confirm(param){
	var title 	= param.title ? param.title : '提示信息';
	var msg 	= param.msg ? param.msg : '真的要执行些项操作吗？';
	vmodal({
		title:title,
		msg:msg,
		footer:'show',
		footerBtn:'<button type="button" class="btn btn-outline red btn-ok">确定</button>',
	},function(){
		$('.ajax-modal .btn-ok').click(function(){
			ajax_post({
				url:param.url,
				data:param.data,
				success:function(ret){
					param.success(ret);
				}
			});				
		});
	});		
}


function loadurl(url,tag,is_obj){
	if(is_obj!= undefined && is_obj==1){
		tag.html('<div class="text-center"><img src="/images/loading.gif" alt="loading"></div>').load(url);
	}else {
		$(tag).html('<div class="text-center"><img src="/images/loading.gif" alt="loading"></div>').load(url);
	}
}

/**
 * 七牛缩略蓝图
 * @param {Object} imgurl
 * @param {Object} w
 * @param {Object} h
 */
function imgwh(imgurl,w,h){
	var url = imgurl + '?imageMogr2/thumbnail/!'+w+'x'+h+'r/gravity/Center/crop/'+w+'x'+h;
	return url;
}


/**
 * 银宝付款
 */
function lurpak_pay(obj){
	var formid = '#'+obj.attr('id');
	var data = getFormJson(formid);
	if(data.safe_psw == ''){
		toast({msg:'请输入安全密码！'});
		return false;
	}
	submitForm({
		formid:formid,
		url:'/pay/pay',
		success:function(ret){
			//console.log(ret);
			if(ret.code == 1){
				toast({code:1,msg:ret.msg});
				setTimeout(function(){
					location.href = '/pay/pay_result/res/success/code/'+ret.data.code;
				},1000);
			}else{
				toast({code:0,msg:ret.msg});
				//location.href = '/pay/pay_result/res/error/code/'+ret.data.code;
			}
		}
	});
	return false;
}


/**
 * 分页
 */
function page_html(param){
	var count 	= param.count ? param.count : 0;
	var p 		= param.p ? param.p : 1;
	var page 	= param.page ? param.page : 0;
	var pagesize= param.pagesize ? param.pagesize : 20;

	count 		= parseInt(count);
	p			= parseInt(p);
	page		= parseInt(page);
	pagesize	= parseInt(pagesize);

	if(page > 1){
		var first = '<div class="btn-p page-s ' + (p < 2 ? 'disabled' : '') + '" data-p="'+ (p > 2 ? p-1 : 1) +'">上一页</div>';
		first 	+= '<div class="btn-p page-no ' + (p == 1 ? 'active' : '') + '" data-p="1">1</div>';

		var last = '<div class="btn-p page-no ' + (p == page ? 'active' : '') + '" data-p="'+ page +'">' + page + '</div>';
		last 	+= '<div class="btn-p page-s ' + (p >= page ? 'disabled' : '') + '" data-p="'+ (p >= page ? page : p+1 ) +'">下一页</div>';

		var page_num = new Array();
		if (page < 9) {
			for (i = 2; i < page; i++) {
				page_num.push(i);
			}
		} else if (p >= 6 && (p + 2) < page) {
			//alert(p);
			page_num.push('');
			page_num.push(p-2);
			page_num.push(p-1);
			page_num.push(p);
			page_num.push(p+1);
			page_num.push(p+2);
			page_num.push('');
		} else if (p <= 5 && page >= 8) {
			for (i = 2; i <= 7; i++) {
				page_num.push(i);
			}
			page_num.push('');
		} else if (page - p <= 4) {
			page_num.push('');
			for (i = page - 7; i < page; i++) {
				page_num.push(i);
			}
		}

		var middle = '';
		for(i=0;i<page_num.length;i++){
			if (page_num[i] == '') middle += '<div class="page-nobox">…</div>';
			else middle += '<div class="btn-p page-no' + (p == page_num[i] ? ' active' : '') + '" ' + (p != page_num[i] ? ' data-p="' + page_num[i] +'"' : '') + '>' + page_num[i] + '</div>';
		}

		var total = '<div class="page-total">'+ param.count +'条记录/共'+ page +'页</div>';
		var html = first + middle + last + total;
		return html;
	}else if(page == 1){
		var total = '<div class="page-total">'+ param.count +'条记录/共'+ page +'页</div>';
		return total;
	}

}

