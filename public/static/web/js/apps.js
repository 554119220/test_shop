$(document).ready(function(){
		var height 	= $(window).height();
		var width	= $(window).width();
		$('.p-content').css({'min-height':height-60});

		$(window).on('resize', function () {
			var height 	= $(window).height();
			var width	= $(window).width();
			$('.p-content').css({'min-height':height-60});
    	}).resize();

});

//文本复制
function copyText(id)
{
	var Url=document.getElementById(id);
	Url.select(); // 选择对象
	document.execCommand("Copy"); // 执行浏览器复制命令
	toast({code:1,msg:'复制成功！'});
}



/**
 * 手机号码格式验证
 * @param mobile
 * @returns {boolean}
 */
function validatemobile(mobile){
    var myreg = /^1[0-9]{10}$/;//手机号码第二位不验证，确保长期有效
    if(!myreg.test(mobile))
    {
        return false;
    }
    return true;
}

/**
 * 字符检测函数
 * @param chr
 * @returns {number}
 */
function char_test(chr)
{
    var i;
    var smallch="abcdefghijklmnopqrstuvwxyz";
    var bigch="ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    for(i=0;i<26;i++)
        if(chr==smallch.charAt(i) || chr==bigch.charAt(i))
            return(1);
    return(0);
}

/**
 * 数字和特殊字符检测函数
 * @param chr
 * @returns {number}
 */
function spchar_test(chr)
{
    var i;
    var spch="_-.0123456789";
    for (i=0;i<13;i++)
        if(chr==spch.charAt(i))
            return(1);
    return(0);
}

/**
 * 检测email格式
 * @param str
 * @returns {number}
 */
function email_test(str)
{
    var i,flag=0;
    var at_symbol=0;
//“@”检测的位置
    var dot_symbol=0;
//“.”检测的位置
    for (i=1;i<str.length;i++)
        if(str.charAt(i)=='@')
        {
            at_symbol=i;
            break;
        }
//检测“@”的位置

    if(at_symbol==str.length-1 || at_symbol==0)
        return(2);
//没有邮件服务器域名

    if(at_symbol<3)
        return(3);
//帐号少于三个字符

    if(at_symbol>19 )
        return(4);
//帐号多于十九个字符

    for(i=1;i<at_symbol;i++)
        if(char_test(str.charAt(i))==0 && spchar_test(str.charAt(i))==0)
            return (5);
    for(i=at_symbol+1;i<str.length;i++)
        if(char_test(str.charAt(i))==0 && spchar_test(str.charAt(i))==0)
            return (5);
//不能用其它的特殊字符

    for(i=at_symbol+1;i<str.length;i++)
        if(str.charAt(i)=='.') dot_symbol=i;
    for(i=at_symbol+1;i<str.length;i++)
        if(dot_symbol==0 || dot_symbol==str.length-1)
//简单的检测有没有“.”，以确定服务器名是否合法
            return (6);

    return (0);
//邮件名合法
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
		beforeSubmit:function(){
			if(params.beforeSubmit) return params.beforeSubmit();
		},
		target: '#ajax_form',
		url: url,
		type: 'POST',
		dataType:'json',
		success:function(ret){
			//console.log(ret);
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
                    console.log(parent);
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

function thumb(imgurl,w,h){
	var url = '/Thumb/index?src='+imgurl+'&w='+w+'&h='+h;
	return url;
}


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

/**
 * 上传头像
 * @param obj
 * @returns {boolean}
 */
function uploadImagesAvatar(obj){
    var field = obj.closest('.form-group').find('input').attr('id');
    $('#form-upload #field').val(field);
    $('#form-upload input[type="file"]').click();

    $('#form-upload input[type="file"]').unbind().change(function(){
        submitForm({
            formid:'#form-upload',
            url:'/upload/avatar',
            success:function(ret){
                //$('.avatar').attr('src','/Thumb/index?src='+ret.data.url+'&w=100&h=100&zc=2');
                $('.avatar').attr('src',ret.data.url);
                if(ret.code != 1){
                	alert('头像上传失败！');
				}
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

function pagenation(obj){
	if(!obj) return;
	var url = obj.data('url');
	var target = obj.data('target');
	var is_ajax = obj.data('ajax');
	obj.find('.btn-p').not('.disabled').click(function(){
		var gourl = url.replace(/(\/p\/\d+)+|(p=\d+.)+/,'') + '/p/'+$(this).data('p');
		//console.log(gourl);
		//obj.attr('data-url',gourl);
		if(is_ajax != undefined && is_ajax == 1) loadurl(gourl,target);
		else window.location.href=gourl;
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
	console.log(name);
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

				}else toast(ret);
			}
		});	
	}
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
 * 防重复提交
 */
function clickDisabled() {
	//点击后三秒内不能给再点击
	$(".btn-submit").click(function() {
	 	$(this).addClass("disabled");
	 	setTimeout("$('.btn-submit').removeClass('disabled');", 3000);
	})
}

//表单验提交
function checkform(param){
	var rules = param.rules ? param.rules : {};
	var messages = param.messages ? param.messages : {};
	
	var form1 = $(param.formid);
	var d = form1.data();
	
	if(param.url == undefined && d.url != '') param.url = d.url;
	
	form1.validate({
		errorClass: "help-block animation-slideDown",
		errorElement: "div",
		errorPlacement: function(e, a) {
			//console.log(a.closest(".group-item").find('.help-block').html());
			if(a.closest(".group-item").find('.help-block').size()>0) a.closest(".group-item").find('.help-block').append(e);
			else a.parents(".form-group > div").append(e)
		},
		highlight: function(e) {
			$(e).closest(".form-group").removeClass("has-success has-error").addClass("has-error"),
			$(e).closest(".help-block").remove()
		},
		success: function(e) {
			e.closest(".form-group").removeClass("has-success has-error"),
			e.closest(".help-block").remove()
		},
		rules: rules,

		messages: messages,

		invalidHandler: function(event, validator) { //验证前错误提示
			// success1.hide();
			//error1.show();
		},

		submitHandler: function(form) { //验证完成后操作		
			
			submitForm({
				formid:param.formid,
				beforeSubmit:param.beforeSubmit,
				url:param.url,
				success:function(ret){
					if(param.success) param.success(ret);
				}
			});
			

			return false;
		}
	});	
	
}

//表单验提交
function checkform4(param){
    var rules = param.rules ? param.rules : {};
    var messages = param.messages ? param.messages : {};

    var form1 = $(param.formid);
    var d = form1.data();

    if(param.url == undefined && d.url != '') param.url = d.url;

    form1.validate({
		rules: rules,

        messages: messages,

        invalidHandler: function(event, validator) { //验证前错误提示
            // success1.hide();
            //error1.show();
        },

        submitHandler: function(form) { //验证完成后操作

            submitForm({
                formid:param.formid,
                beforeSubmit:param.beforeSubmit,
                url:param.url,
                success:function(ret){
                    if(param.success) param.success(ret);
                }
            });


            return false;
        }
    });

}
function checkform10(param){
    var rules = param.rules ? param.rules : {};
    var messages = param.messages ? param.messages : {};

    var form1 = $(param.formid);
    var d = form1.data();

    if(param.url == undefined && d.url != '') param.url = d.url;

    form1.validate({
        rules: rules,

        messages: messages,

        invalidHandler: function(event, validator) { //验证前错误提示
            // success1.hide();
            //error1.show();
        },

        submitHandler: function(form) { //验证完成后操作
            var parent  = $(param.formid).closest('form');
            var params2  = {
                header : getHeader(param.header),
                data : getForm(parent),
                success:function(ret){
                    if(param.success) param.success(ret);
                }
            };
            apiPost(params2);
            return false;
        }
    });

}

//表单验提交加再次确认
function checkform2(param){
    var rules = param.rules ? param.rules : {};
    var messages = param.messages ? param.messages : {};

    var form1 = $(param.formid);
    var d = form1.data();

    if(param.url == undefined && d.url != '') param.url = d.url;

    form1.validate({
        errorClass: "help-block animation-slideDown",
        errorElement: "div",
        errorPlacement: function(e, a) {
            //console.log(a.closest(".group-item").find('.help-block').html());
            if(a.closest(".group-item").find('.help-block').size()>0) a.closest(".group-item").find('.help-block').append(e);
            else a.parents(".form-group > div").append(e)
        },
        highlight: function(e) {
            $(e).closest(".form-group").removeClass("has-success has-error").addClass("has-error"),
                $(e).closest(".help-block").remove()
        },
        success: function(e) {
            e.closest(".form-group").removeClass("has-success has-error"),
                e.closest(".help-block").remove()
        },
        rules: rules,

        messages: messages,

        invalidHandler: function(event, validator) { //验证前错误提示
            // success1.hide();
            //error1.show();
        },

        submitHandler: function(form) { //验证完成后操作
            vmodal({
                title:'确定提交吗？',
                url:'/account/ajaxbanktransferadd',
                width:'600px',
                footer:'hide',
            });
            return false;
        }
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
			selectCity();
			loadurl();
			loading();			
        }
    };	
}();