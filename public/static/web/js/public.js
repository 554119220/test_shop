/**
 * Created by Mercury on 2017/12/5 0005.
 */
$(function () {
    image_zoom();
    //  设置active tab
    var activeTab = $(".row-tabs").data('tab');
    activeTab = activeTab ? activeTab : '';
    $(".a-tab[data-tab='"+activeTab+"']").addClass('cp-active');

    //  侧边栏导航
    var activeController = $(".tab-controller").data('controller');
    activeController = activeController ? activeController : '';
    $(".a-side[data-controller='"+activeController+"']").addClass('active');

    //  分页跳转
    $(".btn-redirect-page").click(function () {
        var input       = $("#redirect-page");
        var input_page  = parseInt(input.val());
        var max_page    = parseInt(input.attr('max'));
        var current_page= parseInt($(this).data('current'));
        if (input_page > max_page || input_page < 1) {
            error('页面介于1-' + max_page + '之间');
            return;
        }
        if (current_page == input_page) {
            error('您现在就在第' + input_page + '页哟！~');
            return;
        }
        var href= $(this).data('href');
        var url = href.replace('page_num', input_page);
        window.location.href = url;
    });

    /**
     * 数据提交
     * form 参数 redirect,refresh,redirectParams
     */
    $(".btn-submit").click(function () {
        var parent  = $(this).closest('form');
        var header  = parent.data('header');
        var data    = parent.data();
        if (data.confirm !== undefined) {
            if (confirm(data.confirm) === false) return;
        }
        var params  = {
            header : getHeader(header),
            data : getForm(parent),
            success : function (ret) {
                if (ret.code === 20000) {
                    success(ret.msg);
                    //  执行跳转
                    if (data.redirect !== undefined) {
                        var url = data.redirect;
                        if (ret.data !== undefined) {
                            for (var i in ret.data) {

                            }
                        }
                        window.location.href = url;
                    }
                    //  执行刷新
                    if (data.refresh !== undefined) {
                        window.location.reload();
                    }
                } else {
                    error(ret.msg);
                }
            }
        };
        apiPost(params);
    });

    //  地区选择
    $("select.change-district").change(function () {
        var level   = parseInt($(this).data('level'));
        var id      = $(this).val();
        var next_level = level + 1;
        var html = '<option value="">--请选择--</option>';
        //  清除下级数据
        for (var i = next_level; i < 5; i++) {
            $("select.district-level-" + i).html(html);
        }
        //  第四级不在请求服务器
        if (level === 4) return;
        var params = {
            header : getHeader('district'),
            data : {id : id},
            success : function (ret) {
                if (ret.code === 20000) {
                    if (level === 3) {  //开启第四集
                        $("select.district-level-4").show();
                    }
                    for (var i in ret.data) {
                        html += '<option value="'+ret.data[i].id+'">'+ret.data[i].a_name+'</option>'
                    }
                    $("select.district-level-"+next_level).html(html);
                } else {
                    //  如果第四级没有数据则隐藏
                    if (next_level === 4) {
                        $("select.district-level-4").hide();
                    }
                }
            }
        };
        apiPost(params)
    });

    //  搜索
    $(document).on('click','#search-btn',function(){
        if($('#search-main').hasClass('hide')){
            $('#search-main').removeClass('hide')
        }else{
            $('#search-main').addClass('hide')
        }
    })
});

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
 * 获取header参数
 *
 * @param header_name
 */
function getHeader(header_name) {
    return JSON.parse($("meta[name='headers-"+header_name+"']").attr('content'));
}

/**
 * 获取表单数据
 *
 * @param from
 * @returns {*|jQuery}
 */
function getForm(form) {
    return form.serialize();
}

/**
 * api post
 * @author Lzy
 * @date 2017-11-22 11:20:00
 *
 * @params.header 请求的url
 * @params.data 请求的数据
 * @params.success 成功的回调
 * @params.error 失败的回调
 * @params.log 是否写日志
 */
function apiPost(params){
    params.url = params.url === undefined ? '/api' : params.url;
    $.ajax({
        type: 'post',
        url: params.url,
        data: params.data,
        dataType: 'json',
        headers: params.header,
        async: true,
        success: function (ret) {
            if ( params.log ) {
                console.log(ret);
            }
            if ( params.success ) {
                params.success(ret);
            }
        },
        error: function(e){
            if ( params.error ) {
                params.error(e);
            } else {
                error('请求错误，请稍后重试')
            }
        },
    });
    return false;
}

/**
 * 图片上传
 *
 * @param that
 */
function uploadImages(that) {
    //upload
    var $this   = $(that);
    var images  = $this[0].files[0];
    var data    = new FormData();
    var input   = $this.data('input');
    var max     = $this.data('max');
    //  多出一个元素，所以就+1
    max = (max ? max : 5) + 1;
    input = input ? input : 'images';
    input = $('#' + input);
    var value   = input.val();
    var values  = [];
    if (value !== undefined) {
        values  = value.split(',');
        if (values.length >= max) {
            error('最多可上传' + max + '张图片');
            return;
        }
    }
    loading({msg:'图片上传中...'});
    data.append('file', images);
    $.ajax({
        url : '/upload',
        type : 'post',
        check : false,
        contentType : false,
        processData : false,
        data : data,
        success : function (ret) {
            //图片数量限制
            //图片唯一限制
            loading({display:'hide'});
            if (ret.info === 'success') {
                var index = values.indexOf(ret.data.key);
                if (index === -1) {
                    values.push(ret.data.key);
                    values = values.join(',');
                    input.val(values);
                    var html = '<div class="camera_img">'
                        + '<img data-src="'+ret.data.key+'" src="'+ ret.data.url +'">'
                        + '<span onclick="btnDeleteImages(this)" title="删除图片" class="btn-delete-images">×</span>'
                        + '</div>';
                    $this.closest(".fileinput-button").next().append(html);
                }
            } else {
                error(ret.msg);
            }
        },
        error : function (xhr) {
            error('网络错误');
        }
    });
}

/**
 *
 * 图片上传
 *
 * @param that
 */
function btnDeleteImages(that) {
    var $this = $(that);
    var key = $this.prev().data('src');
    var input   = $this.closest('.box-images').find('input').data('input');
    input = input ? input : 'images';
    input = $('input[name="'+input+'"]');
    var value = input.val();
    $this.closest('div').remove();
    if (value === '' || value === undefined) {
        return false;
    }
    var values = value.split(',');
    var index = values.indexOf(key);
    values.splice(index, 1);
    values = values.join(',');
    input.val(values);
}

/**
 * loading
 *
 * @param msg
 * @param display
 */
function loading(params) {
    var $this   = $(".loading");
    if (params.msg !== undefined) $this.html(params.msg);
    if (params.display !== undefined) {
        $this.addClass('hide');
    } else {
        $this.removeClass('hide');
    }
}

function error(msg) {
    $(".toast-error").parent().remove();
    toastr.error(msg, '错误提示', {
        "positionClass": "toast-bottom-center margin-bottom-10",
    });
}
function success(msg) {
    toastr.success(msg, '提示', {
        "positionClass": "toast-bottom-center margin-bottom-10",
    });
}

/**
 * 点击触发选择图片上传
 * @return {[type]} [description]
 * @param valueEle 替换值的input element
 * @param imgEle 替换图片的img element
 */
var uploadImagesBaseValueEle = '';
var uploadImagesBaseImgEle = '';
var uploadSuccessFun = '';
var uploadImagesBaseZoomEle = '';
function triggerUploadImages(valueEle,imgEle,callback, param){
    var param = param ? param : {};
    uploadImagesBaseValueEle    = valueEle  ? valueEle  : '';
    uploadImagesBaseImgEle      = imgEle    ? imgEle    : '';
    uploadSuccessFun            = callback  ? callback  : '';
    uploadImagesBaseZoomEle     = param.uploadImagesBaseZoomEle  ? param.uploadImagesBaseZoomEle  : '';

    $('#upload-images-base').click();
}
/**
 * 图片上传,在页面加入下面一行代码开始使用，并使用triggerUploadImages()来触发上传
 * <input class="upload-file" style="display: none;" id="upload-images-base" type="file" onchange="uploadImagesBase();">
 * @author Lzy
 * @date 2017-21-01 15:00:00
 * 上传后若要做其他操作，应该用valueEle的onchange事件
 */
function uploadImagesBase(callback) {
    var fileObj = $('#upload-images-base');
    if ( fileObj.val() == '' ) {
        return ;
    }
    var images  = fileObj[0].files[0];
    var data    = new FormData();
    data.append('file', images);
    $.ajax({
        url : '/upload',
        type : 'post',
        check : false,
        contentType : false,
        processData : false,
        data : data,
        success : function (ret) {
            if (ret.info == 'success') {
                console.log(ret);
                // 图片替换
                if(uploadImagesBaseImgEle){
                    $(uploadImagesBaseImgEle).attr('src', ret.data.url);
                }
                // 值替换
                if(uploadImagesBaseValueEle){
                    $(uploadImagesBaseValueEle).val(ret.data.key);
                }
                // 回调函数
                if(uploadSuccessFun){
                    uploadSuccessFun(ret);
                }
                // 放大
                if(uploadImagesBaseZoomEle){
                    $(uploadImagesBaseZoomEle).attr('href', ret.data.url);
                }
            } else {
                error(ret.msg);
            }
            uploadImagesBaseValueEle    = '';
            uploadImagesBaseImgEle      = '';
            uploadSuccessFun            = '';
            uploadImagesBaseZoomEle     = '';
            $('#upload-images-base').val('');
        },
        error : function (xhr) {
            error('网络错误');
            $('#upload-images-base').val('');
        }
    });
}
/**
 * 点击触发选择图片上传
 * @return {[type]} [description]
 * @param valueEle 替换值的input element
 * @param imgEle 替换图片的img element
 */
var uploadImagesParams = new Object;
function triggerUploadImagesNew(param){
    uploadImagesParams = param ? param : {};
    $('#upload-images-base-new').click();
}
/**
 * 图片上传,在页面加入下面一行代码开始使用，并使用triggerUploadImages()来触发上传
 * <input class="upload-file" style="display: none;" id="upload-images-base-new" type="file" onchange="uploadImagesBaseNew();">
 * @author Lzy
 * @date 2017-21-01 15:00:00
 * 上传后若要做其他操作，应该用valueEle的onchange事件
 */
function uploadImagesBaseNew() {
    var fileObj = $('#upload-images-base-new');
    if ( fileObj.val() == '' ) {
        return ;
    }
    var images  = fileObj[0].files[0];
    var data    = new FormData();
    data.append('file', images);
    $.ajax({
        url : uploadImagesParams.url ? uploadImagesParams.url : '/uploadImage',
        type : 'post',
        check : false,
        contentType : false,
        processData : false,
        data : data,
        async : true,
        success : function (ret) {
            console.log(ret)
            if (ret.info == 'success') {
                // 图片替换
                if(uploadImagesParams.imgEle){
                    $(uploadImagesParams.imgEle).attr('src', ret.data.url);
                }
                // 值替换
                if(uploadImagesParams.valueEle){
                    $(uploadImagesParams.valueEle).val(ret.data.key);
                }
                // 回调函数
                if(uploadImagesParams.callback){
                    uploadImagesParams.callback(ret);
                }
                // 放大
                if(uploadImagesParams.zoomEle) {
                    $(uploadImagesParams.zoomEle).attr('href', ret.data.url);
                }
            } else {
                error(ret.msg);
            }
            uploadImagesParams = new Object;
            $('#upload-images-base-new').val('');
        },
        error : function (xhr) {
            error('网络错误');
            $('#upload-images-base-new').val('');
        }
    });
}
//将form中的值转换为键值对
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

// 模态框
function MyModal(param,callback){
    // tag
    var tag = param.tag ? param.tag : '#modal';
    // 注销点击事件 防止多次注册点击事件，注册点击事件代码应为 $(document).on('click.modal','#modal .modal-ok',function(){});
    $(document).off('click.modal');
    // 设置宽度
    var width = param.width ? param.width : '1000';
    $(tag).find('.modal-dialog').css('width', width + 'px');
    // 标题
    var title = param.title ? param.title : '提示窗口';
    $(tag).find('.modal-title').html(title);
    // confirm
    var confirm = '';
    confirm += '<div class="center mt20">';
    confirm += '<a href="javascript:;" class="btn btn-success btn-lg modal-ok"> 确定 </a>';
    confirm += '<a href="javascript:;" class="btn btn-default btn-lg modal-cancel ml20" data-dismiss="modal"> 取消 </button>';
    confirm += '</div>';
    // 参数
    var options = new Array();
    // 点击阴影部分 是否 取消模态框
    if(param.backdrop == false){
        options['backdrop'] = false;
    } else {
        options['backdrop'] = true;
    }
    // 隐藏底部
    if(param.footer == false) {
        $(tag).find('.modal-footer').addClass('hide');
    } else {
        $(tag).find('.modal-footer').removeClass('hide');
    }
    // 内容设置
    $(tag).find('.modal-body').html('<span class="fs18">正在加载内容...</span>');
    if(param.url){
        $(tag).find('.modal-body').load(param.url,function(){
            if ( param.loadcallback ) {
                var loadcallback = param.loadcallback;
                loadcallback();
            }
        });
    } else if (param.msg) {
        if(param.confirm){
            $(tag).find('.modal-body').html('<div class="' + param.class + '">' + param.msg + '</div>' + confirm);
        }else{
            $(tag).find('.modal-body').html('<div class="' + param.class + '">' + param.msg + '</div>');
        }
    }
    // 加载模态框
    $(tag).modal(options);
    // 执行代码
    if(param.script){
        eval(param.script);
    }
    // 回调函数
    if(callback){
        callback();
    }
}


function modal(params) {

}