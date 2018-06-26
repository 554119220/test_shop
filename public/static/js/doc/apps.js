$(document).ready(function(){

	appInit.init();
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


function image_zoom(param){
	var tag=$('body');	
	if(param && param.obj) var tag=param.obj;
	else if(param && param.tag) var tag=$(param.tag);
	//console.log(tag);
	if(tag.find('.image-zoom').size()>0){
		tag.find('.image-zoom').magnificPopup({ 
			type: 'image',
			mainClass: 'mfp-with-zoom', // this class is for CSS animation below
			zoom: {
				enabled: true, // By default it's false, so don't forget to enable it
				duration: 300, // duration of the effect, in milliseconds
				easing: 'ease-in-out', // CSS transition easing function 
				opener: function(openerElement) {
					var parent = $(openerElement);
					return parent;
				}
			}
		});	
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
 * 上传图片
 */
/*
function uploadImages(obj){
	var field = obj.closest('.form-group').find('input').attr('id');
	$('#form-upload #field').val(field);
	$('#form-upload input[type="file"]').click();
	
	$('#form-upload input[type="file"]').unbind().change(function(){
		lrz(this.files[0], {
			width: 1200,
		}).then(function(rst) {
			//console.log(rst);
			var data = {filebody:rst.base64,type:rst.file.type,filesize:rst.file.size};
			//console.log(data);
			ajax_post({
				url:'/upload/images',
				data:data,
				success:function(ret){
					//console.log(ret);
					obj.closest('.form-group').find('input').val(ret.data.url);
					var html = '<li><div class="sub-action"><i class="fa fa-times" onclick="deleteImage($(this))"></i></div><a class="image-zoom" href="'+ret.data.url+'"><img src="'+ imgwh(ret.data.url,100,100) +'" alt=""></a></li>';
					//console.log(html);
					obj.closest('.form-group').find('.form-images-list').html(html);
					image_zoom();
				}
			});
		}).catch(function(err) {
			toast({msg:'上传失败！'});
		}).always(function() {
			//不管是正常还是错误都输出
		});
		
		obj.val('');
	});
}
*/
function uploadImages(obj){
	var field = obj.closest('.form-group').find('input').attr('id');
	$('#form-upload #field').val(field);
	$('#form-upload input[type="file"]').click();
	
	$('#form-upload input[type="file"]').unbind().change(function(){
		submitForm({
			formid:'#form-upload',
			url:'/upload/images2',
			success:function(ret){
					obj.closest('.form-group').find('input').val(ret.data.url);
					var html = '<li><div class="sub-action"><i class="fa fa-times" onclick="deleteImage($(this))"></i></div><a class="image-zoom" href="'+ret.data.url+'"><img src="'+ imgwh(ret.data.url,100,100) +'" alt=""></a></li>';
					//console.log(html);
					obj.closest('.form-group').find('.form-images-list').html(html);
					image_zoom();				
			}
		});
		$(this).val('');
	});
	
	return false;
}


function uploadImagesMore(obj){
	var field = obj.closest('.form-group').find('input').attr('id');
	$('#form-upload #field').val(field);
	$('#form-upload input[type="file"]').click();
	
	$('#form-upload input[type="file"]').unbind().change(function(){
		submitForm({
			formid:'#form-upload',
			url:'/upload/images2',
			success:function(ret){					
				if(obj.find('.img-item[data-url="'+ret.data.url+'"]').size() == 0){
					var html = '<li class="img-item" data-url="'+ret.data.url+'"><input type="hidden" name="'+field+'[]" value="'+ret.data.url+'"><div class="sub-action"><i class="fa fa-times" onclick="deleteImageMore($(this))"></i></div><a class="image-zoom" href="'+ret.data.url+'"><img src="'+ imgwh(ret.data.url,100,100) +'" alt=""></a></li>';
					obj.closest('.form-group').find('.form-images-list-more').append(html);
					image_zoom();
				}			
								
			}
		});
		$(this).val('');
	});
	
	return false;	
}

function deleteImageMore(obj){
	var obj1 = obj.closest('.form-group').find('input');
	var obj2 = obj.closest('.form-group').find('.form-images-list');
	vmodal({
		title:'删除图片',
		msg:'<h3 class="text-center">真的要删除图片吗？</h3>',
		footer:'show',
		footerBtn:'<button type="button" class="btn btn-outline red btn-ok">确定</button>',
		width:650,
	},function(){
		$('.ajax-modal .btn-ok').click(function(){
			obj.closest('li').remove();
			$('.ajax-modal').modal('hide');
		});
	});		
}

function deleteImage(obj){
	var obj1 = obj.closest('.form-group').find('input');
	var obj2 = obj.closest('.form-group').find('.form-images-list');
	vmodal({
		title:'删除图片',
		msg:'<h3 class="text-center">真的要删除图片吗？</h3>',
		footer:'show',
		footerBtn:'<button type="button" class="btn btn-outline red btn-ok">确定</button>',
		width:650,
	},function(){
		$('.ajax-modal .btn-ok').click(function(){
			obj1.val('');
			obj2.html('<li><img src="/static/images/work/icon-images-add.png" width="100" height="100" alt="上传图片" onclick="uploadImages($(this))"></li>');
			$('.ajax-modal').modal('hide');
		});
	});		
}


function formUploadImages(){
	$('input[type="file"][data-type="images"]').each(function(){
		var obj = $(this);
		


	});
	
}

/**
 * 删除接口参数
 */
function delete_params(obj){	
	obj.closest('tr').remove();
}

/**
 * 接口参数事件
 */
function api_params_event(){
	$('[data-type="api-params"] tbody tr').unbind().mouseover(function(){
		var td = $(this).find('td').last();
		if(td.find('.sub-action').size() == 0){
			var html = '<div class="sub-action"><div class="btn red" onclick="delete_params($(this))"><i class="fa fa-remove"></i></div></div>';
			td.append(html);
		}
	});		
}

/**
 * 暂无记录
 */
function nors(msg){
	var html = '<div class="text-center nors">'+ msg +'</div>';
	return html;
}


/**
 * 刷新验证码
 */
function refVcode(obj){
	obj.find('img').attr('src','/captcha.html'+'?' + Math.random());
}

function imgwh(imgurl,w,h){
	var url = imgurl + '?imageMogr2/thumbnail/!'+w+'x'+h+'r/gravity/Center/crop/'+w+'x'+h;
	return url;
}

function htmlencode(s){  
    var div = document.createElement('div');  
    div.appendChild(document.createTextNode(s));  
    return div.innerHTML;  
}  
function htmldecode(s){  
    var div = document.createElement('div');  
    div.innerHTML = s;  
    return div.innerText || div.textContent;  
}  


template.helper('imgwh', function (url,w,h) {
	if(url=='') url = 'http://img.trj.cc/1469193729000511.png';
	return imgwh(url,w,h);
});

template.helper('htmldecode', function (str) {
	return htmldecode(str);
});

template.helper('page_html', function (param) {
	return page_html(param);
});

/**
 * ajax_loadurl
 * @param url 要读取的url
 * @param target 标签位置
 */
function loadurl(url,target){
	var obj 	= $(target);
	//var data 	= obj.data();
	var height 	= obj.height();
	var width 	= obj.width();
	var style 	= 'height:'+height+'px;line-height:'+height+'px';

	var html = '<div class="box-mask" style="'+style+'"><div class="loading"><img src="/images/loading.gif" alt="loading"></div></div>';
	obj.html(html).load(url);	
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

function tabs_click(){
	$('[data-type="tabs-click"] li').click(function(){
		var index 	=  $(this).index();
		var data 	= $(this).closest('.nav-tabs').data();
		
		var obj = $(data.tag + ' .tab-pane').eq(index);
		//if(obj.data('script')) eval(obj.data('script'));
		if(obj.data('url') && obj.html()=='') {
			//alert(obj.html());
			loadurl(obj.data('url'),'#'+obj.attr('id'));
		}
	});
}

function pagenation(obj){
	if(!obj) return;
	var url = obj.data('url');
	var target = obj.data('target');
	obj.find('.btn-p').not('.disabled').click(function(){
		var gourl = url.replace(/(\/p\/\d+)+|(p=\d+.)+/,'') + '/p/'+$(this).data('p');
		//console.log(gourl);
		//obj.attr('data-url',gourl);
		loadurl(gourl,target);
	});
}

var appInit = function (){
    var handleDatePickers = function () {
        if (jQuery().datepicker) {
            $('.date-picker').datepicker({
                orientation: "left",
				format:"yyyy-mm-dd",
				language:'zh-CN',
                autoclose: true
            });
        }
    }
	
	var handleDateTimePickers = function (){
        $(".form_meridian_datetime").datetimepicker({
            format: "yyyy-mm-dd HH:ii:ss",
            showMeridian: true,
            autoclose: true,
            pickerPosition: 'bottom-right',
			language:'zh-CN',
            todayBtn: true
        });		
	}
	
	var handleZoom = function(){
		image_zoom();
	}
	
	var handleUeditor = function(){
		$('[data-type="ueditor"]').each(function(){
			var id=$(this).attr('id');
			var str='var ue_'+id+'=UE.getEditor("'+id+'");';
			eval(str);
		});
	}
	
	//代码编辑器
	var codeEditor = function(){
		$('[data-type="code-editor"]').each(function(){
			var obj = $(this);
			var myTextArea = document.getElementById($(this).attr('id'));
			var myCodeMirror = CodeMirror.fromTextArea(myTextArea, {
			            lineNumbers: true,
			            matchBrackets: true,
			            styleActiveLine: true,
			            theme:"material",
			            mode: 'javascript'
			       });
			       
			myCodeMirror.on('change',function(editor,changes){
				var text = editor.getValue();
				//alert(text);
				obj.val(text);
			});

		});
	}
	
	//异步加载页面
	var loadurl = function(){
		$('[data-type="loadurl"]').each(function(){
			var data = $(this).data();
			var height = $(this).height();
			var width = $(this).width();
			var style = 'height:'+height+'px;line-height:'+height+'px';
			//alert(height);
			var html = '<div class="box-mask" style="'+style+'"><div class="loading"><img src="/images/loading.gif" alt="loading"></div></div>';
			$(this).html(html).load(data.url);
			
		});
	}
	
	//loading
	var loading = function(){
		$('[data-type="loading"]').each(function(){
			var height = $(this).height();
			var width = $(this).width();
			var style = 'height:'+height+'px;line-height:'+height+'px';
			//alert(height);
			var html = '<div class="box-mask" style="'+style+'"><div class="loading"><img src="/images/loading.gif" alt="loading"></div></div>';
			$(this).html(html);			
		});		
	}
	
	var touchspinInit = function(){
		$('[data-type="touchspin"]').TouchSpin({
	        verticalbuttons: true,
	    });	
	}
	
    var handleBootstrapSwitch = function() {
        if (!$().bootstrapSwitch) {
            return;
        }
        $('.make-switch').bootstrapSwitch();
    }
    
    var tabsClick = function(){
    	tabs_click();
    }
	
    return {
        //main function to initiate the module
        init: function () {
            handleDatePickers();
			handleDateTimePickers();
			handleZoom();
			handleUeditor();
			codeEditor();
			loadurl();
			loading();
			touchspinInit();
			handleBootstrapSwitch();
			tabsClick();
        }
    };	
}();