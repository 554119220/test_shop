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

function thumb(imgurl,w,h){
	var url = '/thumb/?src='+imgurl+'&w='+w+'&h='+h;
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
					obj.closest('.form-group').find('input').val(ret.data.key);
					var html = '<li><div class="sub-action"><i class="fa fa-times" onclick="deleteImage($(this))"></i></div><a class="image-zoom" href="'+ret.data.url+'"><img src="'+ thumb(ret.data.url,100,100) +'" alt=""></a></li>';
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
					var html = '<li class="img-item" data-url="'+ret.data.url+'"><input type="hidden" name="'+field+'[]" value="'+ret.data.url+'"><div class="sub-action"><i class="fa fa-times" onclick="deleteImageMore($(this))"></i></div><a class="image-zoom" href="'+ret.data.url+'"><img src="'+ thumb(ret.data.url,100,100) +'" alt=""></a></li>';
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
 * 刷新验证码
 */
function refVcode(obj){
	obj.find('img').attr('src','/captcha.html'+'?' + Math.random());
}

/**
 *省份选择
 */
function province(){
	if($('[data-type="city"]').size() > 0){
		$('[data-type="province"]').change(function(){
			var obj = $(this);
			ajax_get({
				url:'/Selectcity/city/upid/'+obj.val(),
				success:function(ret){
					//console.log(ret);
					if(ret.code == 1){
						var html = '<option value="">请选择城市</option>';
						for(i=0;i<ret.data.length;i++){
							html += '<option value="'+ ret.data[i]['id'] +'">'+ ret.data[i]['a_name'] +'</option>';
						}
						$('[data-type="city"]').html(html);
					}else toast(ret);
				}
			});
			
			if($('[data-type="district"]').size() > 0){
				$('[data-type="district"]').html('<option value="">请选择区/县</option>');
			}
			if($('[data-type="town"]').size() > 0){
				$('[data-type="town"]').html('<option value="">请选择镇/街道</option>');
			}
		});
	}
}

function city(){
	if($('[data-type="district"]').size() > 0){
		$('[data-type="city"]').change(function(){
			var obj = $(this);
			ajax_get({
				url:'/Selectcity/city/upid/'+obj.val(),
				success:function(ret){
					//console.log(ret);
					if(ret.code == 1){
						var html = '<option value="">请选择区/县</option>';
						for(i=0;i<ret.data.length;i++){
							html += '<option value="'+ ret.data[i]['id'] +'">'+ ret.data[i]['a_name'] +'</option>';
						}
						$('[data-type="district"]').html(html);
					}else toast(ret);
				}
			});
			
			if($('[data-type="town"]').size() > 0){
				$('[data-type="town"]').html('<option value="">请选择镇/街道</option>');
			}
		});
	}
	
	if($('[data-type="city"]').size() > 0 && $('[data-type="city"]').data('value')){
		var id = $('[data-type="city"]').data('value');
			ajax_get({
				url:'/Selectcity/sameDepthCity/id/'+id,
				success:function(ret){
					//console.log(ret);
					if(ret.code == 1){
						var html = '<option value="">请选择城市</option>';
						for(i=0;i<ret.data.length;i++){
							html += '<option value="'+ ret.data[i]['id'] +'"'+(id == ret.data[i]['id']?' selected':'')+'>'+ ret.data[i]['a_name'] +'</option>';
						}
						$('[data-type="city"]').html(html);
					}else toast(ret);
				}
			});
	}
}

function district(){
	if($('[data-type="town"]').size() > 0){
		$('[data-type="district"]').change(function(){
			var obj = $(this);
			ajax_get({
				url:'/Selectcity/city/upid/'+obj.val(),
				success:function(ret){
					//console.log(ret);
					if(ret.code == 1){
						var html = '<option value="">请选择镇/街道</option>';
						for(i=0;i<ret.data.length;i++){
							html += '<option value="'+ ret.data[i]['id'] +'">'+ ret.data[i]['a_name'] +'</option>';
						}
						$('[data-type="town"]').html(html);
					}else toast(ret);
				}
			});			
		});
	}
	
	if($('[data-type="district"]').size() > 0 && $('[data-type="district"]').data('value')){
		var id = $('[data-type="district"]').data('value');
			ajax_get({
				url:'/Selectcity/sameDepthCity/id/'+id,
				success:function(ret){
					//console.log(ret);
					if(ret.code == 1){
						var html = '<option value="">请选择区/县</option>';
						for(i=0;i<ret.data.length;i++){
							html += '<option value="'+ ret.data[i]['id'] +'"'+(id == ret.data[i]['id']?' selected':'')+'>'+ ret.data[i]['a_name'] +'</option>';
						}
						$('[data-type="district"]').html(html);
					}else toast(ret);
				}
			});
	}	
}

function town(){
	if($('[data-type="town"]').size() > 0 && $('[data-type="town"]').data('value')){
		var id = $('[data-type="town"]').data('value');
			ajax_get({
				url:'/Selectcity/sameDepthCity/id/'+id,
				success:function(ret){
					//console.log(ret);
					if(ret.code == 1){
						var html = '<option value="">请选择镇/街道</option>';
						for(i=0;i<ret.data.length;i++){
							html += '<option value="'+ ret.data[i]['id'] +'"'+(id == ret.data[i]['id']?' selected':'')+'>'+ ret.data[i]['a_name'] +'</option>';
						}
						$('[data-type="town"]').html(html);
					}else toast(ret);
				}
			});
	}	
}

/**
 * 分类选择
 */
function form_select_category(obj){
	var data = obj.data();
	obj.closest('.form-group').find('input').val(data.id);
	obj.addClass('active').siblings().removeClass('active');
	
	var depth 		= parseInt(obj.closest('.item').data('depth'));
	var max_depth 	= parseInt(obj.closest('.select-category-box').data('max_depth'));
	var name 		= obj.closest('.select-category-box').attr('data-name');
	// console.log(name);
	var col			= parseInt(12/max_depth);
	var nav = new Array();
	obj.closest('.form-group').find('.item').each(function(){
		nav.push($(this).find('li.active').text());
	});
	obj.closest('.form-group').find('.navsort .select-record-box-item').html(nav.join(' > '));
	
	var options = obj.closest('.form-group').find('.hide.options').html();
	
	if(depth < max_depth){
		obj.closest('.row').find('.item').each(function(){
			if($(this).data('depth') > depth) $(this).remove();
		});
		ajax_post({
			url:'/Selectcategory/category',
			data:{options:options,upid:data.id},
			success:function(ret){
				//console.log(ret);
				if(ret.code == 1){
					var html = '<div class="col-xs-'+ col +' item" data-depth="'+ (depth+1) +'"><ul>';
					for(i=0;i<ret.data.length;i++){
						html += '<li data-id="'+ ret.data[i].id +'" onclick="form_select_category($(this))">'+ ret.data[i][name] +'</li>';
					}
					html += '</ul></div>';
					obj.closest('.row').append(html);
					
					//商品分类选择的特殊处理
					if($('.work-goods').size()>0){	
						changeGoodsCategory();
					}
				}else toast(ret);
			}
		});	
	}
}

/**
 * 商品分类选择
 */
function changeGoodsCategory(){
	$('#f-category_id').attr('data-init',1);
	$('#f-category_id ul li').unbind().click(function(){		
		var id = $(this).data('id');
		loadurl('/goods/setAttr/cid/'+id,$('.tab-content .tab-pane').eq(1),1);
		loadurl('/goods/setParam/cid/'+id,$('.tab-content .tab-pane').eq(2),1);
	});
}

/**
 * 删除记录选择器内容
 * @param {Object} obj
 */
function deleteSelectRecord(obj){
	vmodal({
		title:'删除',
		msg:'<h3 class="text-center">真的要此项吗？</h3>',
		footer:'show',
		footerBtn:'<button type="button" class="btn btn-outline red btn-ok">确定</button>',
		width:650,
	},function(){
		$('.ajax-modal .btn-ok').click(function(){
			obj.closest('.form-group').find('input').val('');
			obj.closest('.form-group').find('.select-record-box-item').remove();
			$('.ajax-modal').modal('hide');
		});
	});		
}

/**
 * radio box select
 */
function radio_box(obj){
	obj.addClass('active').siblings().removeClass('active');
	var value = obj.data('value');
	obj.closest('.form-group').find('input').val(value);
}

/**
 * 清除缓存
 * 
 * @param {Object} that
 */
function flushCache(that) {
	var $this = $(that);
	var url = $this.data('url');
	var data = 
	ajax_get({
		url : url,
		success : function (ret) {
			toast(ret);
		}
	})
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
	
	//新增接口参数
	var apiParamsAdd = function(){
		$('[data-type="api-params"]').each(function(){
			var html = $(this).find('tbody tr').last().html();
			$(this).closest('.form-group').find('.example').html(html);
		});
		
		api_params_event();	
		$('.btn-api-params-add').click(function(){
			var obj = $(this);
			var tr = '<tr>' + obj.closest('.form-group').find('.example').html() + '</tr>';
			obj.closest('.form-group').find('table tbody').append(tr);
			api_params_event();		
		});		
		
	}
	
	//城市选择器
	var selectCity = function(){
		province();
		city();
		district();
		town();
	}
	
    return {
        //main function to initiate the module
        init: function () {
            handleDatePickers();
			handleDateTimePickers();
			handleZoom();
			handleUeditor();
			codeEditor();
			apiParamsAdd();
			selectCity();
        }
    };	
}();