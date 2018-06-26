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
    params.async = params.async === undefined ? true : params.async;
    $.ajax({
        type: 'post',
        url: params.url,
        data: params.data,
        dataType: 'json',
        headers: params.header,
        async: params.async,
        success: function (ret) {
            //  如果需要登陆
            if (ret.code === 40010) {
                $.router.load('/user/login');
                return;
            }

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
                console.log('请求错误，请稍后重试');
            }
        },
    });
    return false;
}

/**
 * 点击触发选择图片上传
 * @return {[type]} [description]
 */
function triggerUploadImages(){
    $('#upload-images-base').click();
}
/**
 * 图片上传,在页面加入下面一行代码开始使用，并使用triggerUploadImages()来触发上传
 * <input class="upload-file" id="upload-images-base" type="file" onchange="uploadImagesBase();">
 * @author Lzy
 * @date 2017-21-01 15:00:00
 * @param valueEle 替换值的input element
 * @param imgEle 替换图片的img element
 * 上传后若要做其他操作，应该用valueEle的onchange事件
 */
function uploadImagesBase(valueEle,imgEle) {
    var fileObj = $('#upload-images-base');
    var images  = fileObj[0].files[0];
    var data    = new FormData();
    $.showPreloader('图片正在上传');
    data.append('file', images);
    $.ajax({
        url : '/upload',
        type : 'post',
        check : false,
        contentType : false,
        processData : false,
        data : data,
        success : function (ret) {
            $.hidePreloader();
            if (ret.info == 'success') {
                console.log(ret);
                // 图片替换
                if(imgEle){
                    $(imgEle).attr('src', ret.data.url);
                }
                // 值替换
                if(valueEle){
                    $(valueEle).val(ret.data.key);
                }
            } else {
                $.alert(ret.msg);
            }
        }, 
        error : function (xhr) {
            $.hidePreloader();
            console.log('网络错误');
        }
    });
}

/**
 * @param string url
 * @param object data
 * @param params
 */
function apiGet(params) {
    params.url = params.url === undefined ? '/api' : params.url;
    params.async = params.async === undefined ? true : params.async;
    $.ajax({
        type : 'get',
        url : params.url,
        data : params.data,
        dataType : 'json',
        async: params.async,
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
                console.log('请求错误，请稍后重试');
            }
        },
    })
}

/**
 * vue x-template
 *
 * @param el            数据容器
 * @param data          数据
 * @param component     vue 节点
 * @param template      脚本id
 */
function vTemplates(el, data, component, template) {
    component   = typeof component === undefined ? el : component;
    template    = typeof template === undefined ? el : template;

    Vue.component(component, {
        template: '#' + template,
        data : function () {
            return {data : data};
        },
        computed : {
            data : {
                get : function () {
                    return this.data();
                },
                set : function (v) {
                    return this.data = v;
                }
            }
        }
    });
    new Vue({
        el : el,
    });
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
    if (value != undefined || value != '') {
        values  = value.split(',');
        if (values.length >= max) {
            $.alert('最多可上传' + (max - 1) + '张图片');
            return;
        }
    }
    $.showPreloader('图片正在上传');
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
            $.hidePreloader();
            if (ret.info == 'success') {
                var index = values.indexOf(ret.data.key);
                if (index == -1) {
                    values.push(ret.data.key);
                    values = values.join(',');
                    input.val(values);
                    var html = '<div class="camera_img amg">'
                        + '<img data-src="'+ret.data.key+'" src="'+ ret.data.url +'">'
                        + '<span onclick="btnDeleteImages(this)" class="btn-delete-images"></span>'
                        + '</div>';

                    $this.closest(".box-images").append(html);
                }
            } else {
                $.alert(ret.msg);
            }
        }, 
        error : function (xhr) {
            console.log('网络错误');
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
    if (value == '' || value == undefined) {
        return false;
    }
    var values = value.split(',');
    var index = values.indexOf(key);
    values.splice(index, 1);
    values = values.join(',');
    input.val(values);
}

/**
 * 获取header
 *
 * @param content
 * @param header_name
 */
function getHeader(content, header_name) {
    header_name = header_name.indexOf('headers') === -1 ? 'headers_' + header_name : header_name;
    return JSON.parse(content.data(header_name));
}
/**
 * 获取要提交的数据
 *
 * @param content
 */
function getData(content) {
    return content.find('form').serialize();
}

/**
 * vue渲染
 *
 * @param vue_component
 * @param template
 * @param mount
 * @returns {*}
 */
function vms(vue_component, template, mount, url, request_type, methods, inline_vms) {
    var header  = {};
    if (typeof url === 'object') {
        header  = url;
        url = '/api';
    }
    // console.log('run_vms')
    methods = typeof methods === undefined ? {} : methods;
        Vue.component(vue_component, {
            template: template,
            data: function () {
                // console.log('data_fun__' + vue_component);
                return {
                    data: this.$parent.getList()
                }
            },
            methods: methods,
            updated: function () {
                console.log('component_update')
                // this.data.reverse()
                // console.log(this)
                // this.destroyed()
            },
            beforeDestroy: function () {
                console.log('beforeDestroy')
            },
            destroyed: function () {
                console.log('destroyed')
            },

            watch: {
                data: function (old, newd) {
                    // this.data.reverse();
                    // console.log("$options", this.$options)
                    // console.log("watch",old)
                },
                // deep:true
                '$route': function(to, from) {
                    console.log(to);
                    console.log(from)
                    // this.path = this.$router.currentRoute.path
                }
            },
            computed: function () {
                console.log('computed')
            }
        });

    var vm =  new Vue({
        data : {
            data : [],
            key : '123123'
        },
        created : function () {
            // console.log('create')

            this.$mount(mount);
        },
        methods : {
            getList : function () {
                // console.log("this.data",this.data);
                return this.data
            },
            render : function () {
                console.log(this.data)
            },
            request : function (data, callback) {
                var current_page = $(".page-current").attr('id');
                var $this = this;
                var page  = data.page;
                var tab   = $(mount);
                var a = mount.indexOf(current_page);
                if (a === -1) return;
                // console.log("mount:", mount, current_page, a);
                var params= {
                    header : header,
                    url : url,
                    data : data,
                    async : false,
                    success : function (ret) {
                        if (callback === undefined) {
                            if (ret.code === 20000) {
                                var data = ret.data.data;
                                if (data === undefined) data = ret.data;
                                for (var i in data) {
                                    $this.data.push(data[i])
                                    // $this.$set($this.data, i, data[i])
                                }
                                page   += 1;
                                if (page > ret.data.last_page || i < 9) {
                                    tab.attr('data-request', 2);
                                    tab.find(".infinite-scroll-preloader").remove();
                                }
                                // console.log("$children", $this.$children)
                                tab.attr('data-page', page);
                            } else {
                                tab.attr('data-request', 2);
                                tab.find(".infinite-scroll-preloader").remove();
                                if (tab.find('.orders-list-box').html() === '') {
                                    var html = '<div class="mg40 text-center">' +
                                        '<img style="width:8rem" src="/static/wap/images/icon/icon_no_msg.png">' +
                                        '<div class="text_99 rfs14 mt30">没有数据！</div>' +
                                        '</div>';
                                    $(mount).find('.orders-list-box').css("background", "#f8f8f8");
                                    html    = '<div class="db text-center">'+
                                        '<img style="width:5rem;margin: 2rem 0 1rem" src="/static/wap/images/shop_no.png">'+
                                        '<p class="mg0 text_77 rfs16">没有找到数据</p>'+
                                        '<p class="mg0 text_99 rfs14">客官，<a class="text_red" href="/">去商城看看吧~</a></p>'+
                                        '</div>';
                                    tab.find('.orders-list-box').html(html);
                                }
                            }
                        } else {
                            callback(ret, $this);
                        }
                    },
                    error : function () {
                        console.log('error');
                    }
                };
                if (request_type === undefined) {
                    apiPost(params);
                } else {
                    // $this.$nextTick(function () {
                    //     console.log(123213);
                        apiGet(params);
                    // })
                    // $this.$nextTick(()=>{
                    //
                    // });

                }
            }
        },
        mounted : function () {
            // console.log(this.$el)
            // console.log('mounted')
        },
        beforeCreate : function () {
            // console.log('beforeCreate')
        },
        beforeMount : function () {
            // console.log('beforeMount')
        },
        beforeUpdate : function () {
            // this.$mount(mount);

            console.log('beforeUpdate')
        },
        updated : function () {
            // console.log(this.$el);
            // console.log('updated')
        },
        beforeDestroy : function () {
            console.log('beforeDestroy')
        },
        destroyed : function () {
            console.log('destroyed111')
        },
        // watch : function () {
        //     console.log(this);
        //     console.log('watch')
        // }
        // components : {
        //     'orders-list' : {
        //         template: template,
        //         data : function (vm) {
        //             console.log(this);
        //             return {
        //                 data : vm.$parent.getList()
        //             }
        //         },
        //         methods : methods
        //     }
        // }
    });
    // vm.$el  = '11';
    // console.log(vm.$el);
    // vm.$mount(mount);
    return vm;
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
 * 缩略图
 *
 * @param images
 * @param w
 * @param h
 * @returns {string}
 */
function thumb(images, w, h) {
    if (h === undefined || h === '') h = w;
    return images + '?imageMogr2/thumbnail/'+w+'x'+h+'!'
}