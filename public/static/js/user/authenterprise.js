var yyzz = new plupload.Uploader({ //创建实例的构造方法 
    runtimes: 'html5,flash,silverlight,html4', 
    //上传插件初始化选用那种方式的优先级顺序 
    browse_button: 'yyzz', 
    // 上传按钮 
    url: "/userdata/uploadimg", 
    //远程上传地址 
    flash_swf_url: '/css/user/Moxie.swf', 
    //flash文件地址 
    silverlight_xap_url: '/css/user/Moxie.xap', 
    //silverlight文件地址 
    filters: { 
        max_file_size: '10240kb', 
        //最大上传文件大小（格式100b, 10kb, 10mb, 1gb） 
        mime_types: [ //允许文件上传类型 
        { 
            title: "files", 
            extensions: "jpg,png,gif" 
        }] 
    }, 
    multi_selection: true, 
    true:false,//多文件上传, false 单文件上传 ctrl 多文件
    init: { 
        FilesAdded: function(up, files) { //文件上传前 
            if ($("#ul_pics").children("li").length > 30) { 
                                jeBox.msg('您上传的图片太多了！！', {icon: 6});
                uploader.destroy(); 
            } else { 
                var li = ''; 
                plupload.each(files, 
                function(file) { //遍历文件 
                    li += "<li id='" + file['id'] + "'><div class='progress'><span class='bar'></span><span class='percent'>0%</span></div></li>"; 
                }); 
                $("#ul_pics").append(li); 
                yyzz.start(); 
            } 
        }, 
        UploadProgress: function(up, file) { //上传中，显示进度条 
            $("#" + file.id).find('.bar').css({ 
                "width": file.percent + "%" 
            }).find(".percent").text(file.percent + "%"); 
        }, 
        FileUploaded: function(up, file, info) { //文件上传成功的时候触发 
            var data = JSON.parse(info.response);
            //$("#idyyzz").html("<div class='img'><img src='" + data.pic + "'/></div><p>" + data.name + "</p>");
            $("#idyyzz").attr('src',data.pic);
            $("input[name='licence_Photo']").val(data.pic); 
        }, 
        Error: function(up, err) { //上传出错的时候触发 
            jeBox.msg(err.message, {icon: 6});
            $("input[name='licence_Photo']").val(0); 
        } 
    } 
});
yyzz.init();

var zzjg = new plupload.Uploader({ //创建实例的构造方法 
    runtimes: 'html5,flash,silverlight,html4', 
    //上传插件初始化选用那种方式的优先级顺序 
    browse_button: 'zzjg', 
    // 上传按钮 
    url: "/userdata/uploadimg", 
    //远程上传地址 
    flash_swf_url: '/css/user/Moxie.swf', 
    //flash文件地址 
    silverlight_xap_url: '/css/user/Moxie.xap', 
    //silverlight文件地址 
    filters: { 
        max_file_size: '10240kb', 
        //最大上传文件大小（格式100b, 10kb, 10mb, 1gb） 
        mime_types: [ //允许文件上传类型 
        { 
            title: "files", 
            extensions: "jpg,png,gif" 
        }] 
    }, 
    multi_selection: true, 
    true:false,//多文件上传, false 单文件上传 ctrl 多文件
    init: { 
        FilesAdded: function(up, files) { //文件上传前 
            if ($("#ul_pics").children("li").length > 30) {
                jeBox.msg('您上传的图片太多了！！', {icon: 6});
                uploader.destroy(); 
            } else { 
                var li = ''; 
                plupload.each(files, 
                function(file) { //遍历文件 
                    li += "<li id='" + file['id'] + "'><div class='progress'><span class='bar'></span><span class='percent'>0%</span></div></li>"; 
                }); 
                $("#ul_pics").append(li); 
                zzjg.start(); 
            } 
        }, 
        UploadProgress: function(up, file) { //上传中，显示进度条 
//             $("#jinduzzjg").find('.bar').css({ 
//                 "width": file.percent + "%" 
//             }).find(".percent").text(file.percent + "%"); 
        }, 
        FileUploaded: function(up, file, info) { //文件上传成功的时候触发 
            var data = JSON.parse(info.response);
            //$("#idyyzz").html("<div class='img'><img src='" + data.pic + "'/></div><p>" + data.name + "</p>");
            $("#idzzjg").attr('src',data.pic); 
            $("input[name='organization_photo']").val(data.pic);
        }, 
        Error: function(up, err) { //上传出错的时候触发 
            jeBox.msg(err.message, {icon: 6});
            $("input[name='organization_photo']").val(0); 
        } 
    } 
}); 
zzjg.init();

var sw = new plupload.Uploader({ //创建实例的构造方法 
    runtimes: 'html5,flash,silverlight,html4', 
    //上传插件初始化选用那种方式的优先级顺序 
    browse_button: 'sw', 
    // 上传按钮 
    url: "/userdata/uploadimg", 
    //远程上传地址 
    flash_swf_url: '/css/user/Moxie.swf', 
    //flash文件地址 
    silverlight_xap_url: '/css/user/Moxie.xap', 
    //silverlight文件地址 
    filters: { 
        max_file_size: '10240kb', 
        //最大上传文件大小（格式100b, 10kb, 10mb, 1gb） 
        mime_types: [ //允许文件上传类型 
        { 
            title: "files", 
            extensions: "jpg,png,gif" 
        }] 
    }, 
    multi_selection: true, 
    true:false,//多文件上传, false 单文件上传 ctrl 多文件
    init: { 
        FilesAdded: function(up, files) { //文件上传前 
            if ($("#ul_pics").children("li").length > 30) {
                jeBox.msg('您上传的图片太多了！！', {icon: 6});
                uploader.destroy(); 
            } else { 
                var li = ''; 
                plupload.each(files, 
                function(file) { //遍历文件 
                    li += "<li id='" + file['id'] + "'><div class='progress'><span class='bar'></span><span class='percent'>0%</span></div></li>"; 
                }); 
                $("#ul_pics").append(li); 
                sw.start(); 
            } 
        }, 
        UploadProgress: function(up, file) { //上传中，显示进度条 
//             $("#jinduzzjg").find('.bar').css({ 
//                 "width": file.percent + "%" 
//             }).find(".percent").text(file.percent + "%"); 
        }, 
        FileUploaded: function(up, file, info) { //文件上传成功的时候触发 
            var data = JSON.parse(info.response);
            //$("#idyyzz").html("<div class='img'><img src='" + data.pic + "'/></div><p>" + data.name + "</p>");
            $("#idsw").attr('src',data.pic); 
            $("input[name='tax_certificate_photo']").val(data.pic);
        }, 
        Error: function(up, err) { //上传出错的时候触发 
            jeBox.msg(err.message, {icon: 6}); 
        $("input[name='tax_certificate_photo']").val(0);
        } 
    } 
}); 
sw.init();

var idz = new plupload.Uploader({ //创建实例的构造方法 
    runtimes: 'html5,flash,silverlight,html4', 
    //上传插件初始化选用那种方式的优先级顺序 
    browse_button: 'idz', 
    // 上传按钮 
    url: "/userdata/uploadimg", 
    //远程上传地址 
    flash_swf_url: '/css/user/Moxie.swf', 
    //flash文件地址 
    silverlight_xap_url: '/css/user/Moxie.xap', 
    //silverlight文件地址 
    filters: { 
        max_file_size: '10240kb', 
        //最大上传文件大小（格式100b, 10kb, 10mb, 1gb） 
        mime_types: [ //允许文件上传类型 
        { 
            title: "files", 
            extensions: "jpg,png,gif" 
        }] 
    }, 
    multi_selection: true, 
    true:false,//多文件上传, false 单文件上传 ctrl 多文件
    init: { 
        FilesAdded: function(up, files) { //文件上传前 
            if ($("#ul_pics").children("li").length > 30) {
                jeBox.msg('您上传的图片太多了！！', {icon: 6});
                uploader.destroy(); 
            } else { 
                var li = ''; 
                plupload.each(files, 
                function(file) { //遍历文件 
                    li += "<li id='" + file['id'] + "'><div class='progress'><span class='bar'></span><span class='percent'>0%</span></div></li>"; 
                }); 
                $("#ul_pics").append(li); 
                idz.start(); 
            } 
        }, 
        UploadProgress: function(up, file) { //上传中，显示进度条 
//             $("#jinduzzjg").find('.bar').css({ 
//                 "width": file.percent + "%" 
//             }).find(".percent").text(file.percent + "%"); 
        }, 
        FileUploaded: function(up, file, info) { //文件上传成功的时候触发 
            var data = JSON.parse(info.response);
            //$("#idyyzz").html("<div class='img'><img src='" + data.pic + "'/></div><p>" + data.name + "</p>");
            $("#ididz").attr('src',data.pic);
            $("input[name='corporation_f_code']").val(data.pic);
        }, 
        Error: function(up, err) { //上传出错的时候触发 
            jeBox.msg(err.message, {icon: 6}); 
$("input[name='corporation_f_code']").val(0);
        } 
    } 
}); 
idz.init();

var idf = new plupload.Uploader({ //创建实例的构造方法 
    runtimes: 'html5,flash,silverlight,html4', 
    //上传插件初始化选用那种方式的优先级顺序 
    browse_button: 'idf', 
    // 上传按钮 
    url: "/userdata/uploadimg", 
    //远程上传地址 
    flash_swf_url: '/css/user/Moxie.swf', 
    //flash文件地址 
    silverlight_xap_url: '/css/user/Moxie.xap', 
    //silverlight文件地址 
    filters: { 
        max_file_size: '10240kb', 
        //最大上传文件大小（格式100b, 10kb, 10mb, 1gb） 
        mime_types: [ //允许文件上传类型 
        { 
            title: "files", 
            extensions: "jpg,png,gif" 
        }] 
    }, 
    multi_selection: true, 
    true:false,//多文件上传, false 单文件上传 ctrl 多文件
    init: { 
        FilesAdded: function(up, files) { //文件上传前 
            if ($("#ul_pics").children("li").length > 30) {
                jeBox.msg('您上传的图片太多了！！', {icon: 6});
                uploader.destroy(); 
            } else { 
                var li = ''; 
                plupload.each(files, 
                function(file) { //遍历文件 
                    li += "<li id='" + file['id'] + "'><div class='progress'><span class='bar'></span><span class='percent'>0%</span></div></li>"; 
                }); 
                $("#ul_pics").append(li); 
                idf.start(); 
            } 
        }, 
        UploadProgress: function(up, file) { //上传中，显示进度条 
//             $("#jinduzzjg").find('.bar').css({ 
//                 "width": file.percent + "%" 
//             }).find(".percent").text(file.percent + "%"); 
        }, 
        FileUploaded: function(up, file, info) { //文件上传成功的时候触发 
            var data = JSON.parse(info.response);
            //$("#idyyzz").html("<div class='img'><img src='" + data.pic + "'/></div><p>" + data.name + "</p>");
            $("#ididf").attr('src',data.pic);
            $("input[name='corporation_b_code']").val(data.pic); 
        }, 
        Error: function(up, err) { //上传出错的时候触发 
            jeBox.msg(err.message, {icon: 6}); 
$("input[name='corporation_b_code']").val(0);
        } 
    } 
}); 
idf.init();

var ids = new plupload.Uploader({ //创建实例的构造方法 
    runtimes: 'html5,flash,silverlight,html4', 
    //上传插件初始化选用那种方式的优先级顺序 
    browse_button: 'ids', 
    // 上传按钮 
    url: "/userdata/uploadimg", 
    //远程上传地址 
    flash_swf_url: '/css/user/Moxie.swf', 
    //flash文件地址 
    silverlight_xap_url: '/css/user/Moxie.xap', 
    //silverlight文件地址 
    filters: { 
        max_file_size: '10240kb', 
        //最大上传文件大小（格式100b, 10kb, 10mb, 1gb） 
        mime_types: [ //允许文件上传类型 
        { 
            title: "files", 
            extensions: "jpg,png,gif" 
        }] 
    }, 
    multi_selection: true, 
    true:false,//多文件上传, false 单文件上传 ctrl 多文件
    init: { 
        FilesAdded: function(up, files) { //文件上传前 
            if ($("#ul_pics").children("li").length > 30) {
                jeBox.msg('您上传的图片太多了！！', {icon: 6});
                uploader.destroy(); 
            } else { 
                var li = ''; 
                plupload.each(files, 
                function(file) { //遍历文件 
                    li += "<li id='" + file['id'] + "'><div class='progress'><span class='bar'></span><span class='percent'>0%</span></div></li>"; 
                }); 
                $("#ul_pics").append(li); 
                ids.start(); 
            } 
        }, 
        UploadProgress: function(up, file) { //上传中，显示进度条 
//             $("#jinduzzjg").find('.bar').css({ 
//                 "width": file.percent + "%" 
//             }).find(".percent").text(file.percent + "%"); 
        }, 
        FileUploaded: function(up, file, info) { //文件上传成功的时候触发 
            var data = JSON.parse(info.response);
            //$("#idyyzz").html("<div class='img'><img src='" + data.pic + "'/></div><p>" + data.name + "</p>");
            $("#idids").attr('src',data.pic); 
            $("input[name='corporation_h_code']").val(data.pic); 
        }, 
        Error: function(up, err) { //上传出错的时候触发 
            jeBox.msg(err.message, {icon: 6});
            $("input[name='corporation_h_code']").val(0);  
        } 
    } 
}); 
ids.init();

var yyzz2 = new plupload.Uploader({ //创建实例的构造方法 
    runtimes: 'html5,flash,silverlight,html4', 
    //上传插件初始化选用那种方式的优先级顺序 
    browse_button: 'yyzz2', 
    // 上传按钮 
    url: "/userdata/uploadimg", 
    //远程上传地址 
    flash_swf_url: '/css/user/Moxie.swf', 
    //flash文件地址 
    silverlight_xap_url: '/css/user/Moxie.xap', 
    //silverlight文件地址 
    filters: { 
        max_file_size: '10240kb', 
        //最大上传文件大小（格式100b, 10kb, 10mb, 1gb） 
        mime_types: [ //允许文件上传类型 
        { 
            title: "files", 
            extensions: "jpg,png,gif" 
        }] 
    }, 
    multi_selection: true, 
    true:false,//多文件上传, false 单文件上传 ctrl 多文件
    init: { 
        FilesAdded: function(up, files) { //文件上传前 
            if ($("#ul_pics").children("li").length > 30) {
                jeBox.msg('您上传的图片太多了！！', {icon: 6}); 
                uploader.destroy(); 
            } else { 
                var li = ''; 
                plupload.each(files, 
                function(file) { //遍历文件 
                    li += "<li id='" + file['id'] + "'><div class='progress'><span class='bar'></span><span class='percent'>0%</span></div></li>"; 
                }); 
                $("#ul_pics").append(li); 
                yyzz2.start(); 
            } 
        }, 
        UploadProgress: function(up, file) { //上传中，显示进度条 
//             $("#jinduzzjg").find('.bar').css({ 
//                 "width": file.percent + "%" 
//             }).find(".percent").text(file.percent + "%"); 
        }, 
        FileUploaded: function(up, file, info) { //文件上传成功的时候触发 
            var data = JSON.parse(info.response);
            //$("#idyyzz").html("<div class='img'><img src='" + data.pic + "'/></div><p>" + data.name + "</p>");
            $("#idyyzz2").attr('src',data.pic);
            $("input[name='licence_Photo2']").val(data.pic); 
        }, 
        Error: function(up, err) { //上传出错的时候触发 
            jeBox.msg(err.message, {icon: 6});
            $("input[name='licence_Photo2']").val(data.pic); 
        } 
    } 
}); 
yyzz2.init();

var idz2 = new plupload.Uploader({ //创建实例的构造方法 
    runtimes: 'html5,flash,silverlight,html4', 
    //上传插件初始化选用那种方式的优先级顺序 
    browse_button: 'idz2', 
    // 上传按钮 
    url: "/userdata/uploadimg", 
    //远程上传地址 
    flash_swf_url: '/css/user/Moxie.swf', 
    //flash文件地址 
    silverlight_xap_url: '/css/user/Moxie.xap', 
    //silverlight文件地址 
    filters: { 
        max_file_size: '10240kb', 
        //最大上传文件大小（格式100b, 10kb, 10mb, 1gb） 
        mime_types: [ //允许文件上传类型 
        { 
            title: "files", 
            extensions: "jpg,png,gif" 
        }] 
    }, 
    multi_selection: true, 
    true:false,//多文件上传, false 单文件上传 ctrl 多文件
    init: { 
        FilesAdded: function(up, files) { //文件上传前 
            if ($("#ul_pics").children("li").length > 30) {
                jeBox.msg('您上传的图片太多了！！', {icon: 6}); 
                uploader.destroy(); 
            } else { 
                var li = ''; 
                plupload.each(files, 
                function(file) { //遍历文件 
                    li += "<li id='" + file['id'] + "'><div class='progress'><span class='bar'></span><span class='percent'>0%</span></div></li>"; 
                }); 
                $("#ul_pics").append(li); 
                idz2.start(); 
            } 
        }, 
        UploadProgress: function(up, file) { //上传中，显示进度条 
//             $("#jinduzzjg").find('.bar').css({ 
//                 "width": file.percent + "%" 
//             }).find(".percent").text(file.percent + "%"); 
        }, 
        FileUploaded: function(up, file, info) { //文件上传成功的时候触发 
            var data = JSON.parse(info.response);
            //$("#idyyzz").html("<div class='img'><img src='" + data.pic + "'/></div><p>" + data.name + "</p>");
            $("#ididz2").attr('src',data.pic); 
            $("input[name='corporation_f_code2']").val(data.pic);
        }, 
        Error: function(up, err) { //上传出错的时候触发 
            jeBox.msg(err.message, {icon: 6});
            $("input[name='corporation_f_code2']").val(0); 
        } 
    } 
}); 
idz2.init();

var idf2 = new plupload.Uploader({ //创建实例的构造方法 
    runtimes: 'html5,flash,silverlight,html4', 
    //上传插件初始化选用那种方式的优先级顺序 
    browse_button: 'idf2', 
    // 上传按钮 
    url: "/userdata/uploadimg", 
    //远程上传地址 
    flash_swf_url: '/css/user/Moxie.swf', 
    //flash文件地址 
    silverlight_xap_url: '/css/user/Moxie.xap', 
    //silverlight文件地址 
    filters: { 
        max_file_size: '10240kb', 
        //最大上传文件大小（格式100b, 10kb, 10mb, 1gb） 
        mime_types: [ //允许文件上传类型 
        { 
            title: "files", 
            extensions: "jpg,png,gif" 
        }] 
    }, 
    multi_selection: true, 
    true:false,//多文件上传, false 单文件上传 ctrl 多文件
    init: { 
        FilesAdded: function(up, files) { //文件上传前 
            if ($("#ul_pics").children("li").length > 30) {
                jeBox.msg('您上传的图片太多了！！', {icon: 6}); 
                uploader.destroy(); 
            } else { 
                var li = ''; 
                plupload.each(files, 
                function(file) { //遍历文件 
                    li += "<li id='" + file['id'] + "'><div class='progress'><span class='bar'></span><span class='percent'>0%</span></div></li>"; 
                }); 
                $("#ul_pics").append(li); 
                idf2.start(); 
            } 
        }, 
        UploadProgress: function(up, file) { //上传中，显示进度条 
//             $("#jinduzzjg").find('.bar').css({ 
//                 "width": file.percent + "%" 
//             }).find(".percent").text(file.percent + "%"); 
        }, 
        FileUploaded: function(up, file, info) { //文件上传成功的时候触发 
            var data = JSON.parse(info.response);
            //$("#idyyzz").html("<div class='img'><img src='" + data.pic + "'/></div><p>" + data.name + "</p>");
            $("#ididf2").attr('src',data.pic);
            $("input[name='corporation_b_code2']").val(data.pic);  
        }, 
        Error: function(up, err) { //上传出错的时候触发 
            jeBox.msg(err.message, {icon: 6});
            $("input[name='corporation_b_code2']").val(0);  
        } 
    } 
}); 
idf2.init();

var ids2 = new plupload.Uploader({ //创建实例的构造方法 
    runtimes: 'html5,flash,silverlight,html4', 
    //上传插件初始化选用那种方式的优先级顺序 
    browse_button: 'ids2', 
    // 上传按钮 
    url: "/userdata/uploadimg", 
    //远程上传地址 
    flash_swf_url: '/css/user/Moxie.swf', 
    //flash文件地址 
    silverlight_xap_url: '/css/user/Moxie.xap', 
    //silverlight文件地址 
    filters: { 
        max_file_size: '10240kb', 
        //最大上传文件大小（格式100b, 10kb, 10mb, 1gb） 
        mime_types: [ //允许文件上传类型 
        { 
            title: "files", 
            extensions: "jpg,png,gif" 
        }] 
    }, 
    multi_selection: true, 
    true:false,//多文件上传, false 单文件上传 ctrl 多文件
    init: { 
        FilesAdded: function(up, files) { //文件上传前 
            if ($("#ul_pics").children("li").length > 30) {
                jeBox.msg('您上传的图片太多了！！', {icon: 6});
                uploader.destroy(); 
            } else { 
                var li = ''; 
                plupload.each(files, 
                function(file) { //遍历文件 
                    li += "<li id='" + file['id'] + "'><div class='progress'><span class='bar'></span><span class='percent'>0%</span></div></li>"; 
                }); 
                $("#ul_pics").append(li); 
                ids2.start(); 
            } 
        }, 
        UploadProgress: function(up, file) { //上传中，显示进度条 
//             $("#jinduzzjg").find('.bar').css({ 
//                 "width": file.percent + "%" 
//             }).find(".percent").text(file.percent + "%"); 
        }, 
        FileUploaded: function(up, file, info) { //文件上传成功的时候触发 
            var data = JSON.parse(info.response);
            //$("#idyyzz").html("<div class='img'><img src='" + data.pic + "'/></div><p>" + data.name + "</p>");
            $("#idids2").attr('src',data.pic);
            $("input[name='corporation_h_code2']").val(data.pic); 
        }, 
        Error: function(up, err) { //上传出错的时候触发 

            jeBox.msg(err.message, {icon: 6});
        $("input[name='corporation_h_code2']").val(0);  
        } 
    } 
}); 
ids2.init();

//新增工单
var idzheng = new plupload.Uploader({ //创建实例的构造方法 
    runtimes: 'html5,flash,silverlight,html4',
    //上传插件初始化选用那种方式的优先级顺序 
    browse_button: 'idzheng',
    // 上传按钮 
    url: "/userdata/uploadimg",
    //远程上传地址 
    flash_swf_url: '/css/user/Moxie.swf',
    //flash文件地址 
    silverlight_xap_url: '/css/user/Moxie.xap',
    //silverlight文件地址 
    filters: {
        max_file_size: '10240kb',
        //最大上传文件大小（格式100b, 10kb, 10mb, 1gb） 
        mime_types: [ //允许文件上传类型 
            {
                title: "files",
                extensions: "jpg,png,gif"
            }]
    },
    multi_selection: true,
    true:false,//多文件上传, false 单文件上传 ctrl 多文件
    init: {
        FilesAdded: function(up, files) { //文件上传前 
            if ($("#ul_pics").children("li").length > 30) {
                jeBox.msg('您上传的图片太多了！！', {icon: 6});
                uploader.destroy();
            } else {
                var li = '';
                plupload.each(files,
                    function(file) { //遍历文件 
                        li += "<li id='" + file['id'] + "'><div class='progress'><span class='bar'></span><span class='percent'>0%</span></div></li>";
                    });
                $("#ul_pics").append(li);
                idzheng.start();
            }
        },
        UploadProgress: function(up, file) { //上传中，显示进度条 
//             $("#jinduzzjg").find('.bar').css({ 
//                 "width": file.percent + "%" 
//             }).find(".percent").text(file.percent + "%"); 
        },
        FileUploaded: function(up, file, info) { //文件上传成功的时候触发 
            var data = JSON.parse(info.response);
            //$("#idyyzz").html("<div class='img'><img src='" + data.pic + "'/></div><p>" + data.name + "</p>");
            $("#ididzheng").attr('src',data.pic);
            $("input[name='idcard_f_photo']").val(data.pic);
        },
        Error: function(up, err) { //上传出错的时候触发 

            jeBox.msg(err.message, {icon: 6});
            $("input[name='idcard_f_photo']").val(0);
        }
    }
});
idzheng.init();

var idfang = new plupload.Uploader({ //创建实例的构造方法 
    runtimes: 'html5,flash,silverlight,html4',
    //上传插件初始化选用那种方式的优先级顺序 
    browse_button: 'idfang',
    // 上传按钮 
    url: "/userdata/uploadimg",
    //远程上传地址 
    flash_swf_url: '/css/user/Moxie.swf',
    //flash文件地址 
    silverlight_xap_url: '/css/user/Moxie.xap',
    //silverlight文件地址 
    filters: {
        max_file_size: '10240kb',
        //最大上传文件大小（格式100b, 10kb, 10mb, 1gb） 
        mime_types: [ //允许文件上传类型 
            {
                title: "files",
                extensions: "jpg,png,gif"
            }]
    },
    multi_selection: true,
    true:false,//多文件上传, false 单文件上传 ctrl 多文件
    init: {
        FilesAdded: function(up, files) { //文件上传前 
            if ($("#ul_pics").children("li").length > 30) {
                jeBox.msg('您上传的图片太多了！！', {icon: 6});
                uploader.destroy();
            } else {
                var li = '';
                plupload.each(files,
                    function(file) { //遍历文件 
                        li += "<li id='" + file['id'] + "'><div class='progress'><span class='bar'></span><span class='percent'>0%</span></div></li>";
                    });
                $("#ul_pics").append(li);
                idfang.start();
            }
        },
        UploadProgress: function(up, file) { //上传中，显示进度条 
//             $("#jinduzzjg").find('.bar').css({ 
//                 "width": file.percent + "%" 
//             }).find(".percent").text(file.percent + "%"); 
        },
        FileUploaded: function(up, file, info) { //文件上传成功的时候触发 
            var data = JSON.parse(info.response);
            //$("#idyyzz").html("<div class='img'><img src='" + data.pic + "'/></div><p>" + data.name + "</p>");
            $("#ididfang").attr('src',data.pic);
            $("input[name='idcard_b_photo']").val(data.pic);
        },
        Error: function(up, err) { //上传出错的时候触发 

            jeBox.msg(err.message, {icon: 6});
            $("input[name='idcard_b_photo']").val(0);
        }
    }
});
idfang.init();

var idxing = new plupload.Uploader({ //创建实例的构造方法 
    runtimes: 'html5,flash,silverlight,html4',
    //上传插件初始化选用那种方式的优先级顺序 
    browse_button: 'idxing',
    // 上传按钮 
    url: "/userdata/uploadimg",
    //远程上传地址 
    flash_swf_url: '/css/user/Moxie.swf',
    //flash文件地址 
    silverlight_xap_url: '/css/user/Moxie.xap',
    //silverlight文件地址 
    filters: {
        max_file_size: '10240kb',
        //最大上传文件大小（格式100b, 10kb, 10mb, 1gb） 
        mime_types: [ //允许文件上传类型 
            {
                title: "files",
                extensions: "jpg,png,gif"
            }]
    },
    multi_selection: true,
    true:false,//多文件上传, false 单文件上传 ctrl 多文件
    init: {
        FilesAdded: function(up, files) { //文件上传前 
            if ($("#ul_pics").children("li").length > 30) {
                jeBox.msg('您上传的图片太多了！！', {icon: 6});
                uploader.destroy();
            } else {
                var li = '';
                plupload.each(files,
                    function(file) { //遍历文件 
                        li += "<li id='" + file['id'] + "'><div class='progress'><span class='bar'></span><span class='percent'>0%</span></div></li>";
                    });
                $("#ul_pics").append(li);
                idxing.start();
            }
        },
        UploadProgress: function(up, file) { //上传中，显示进度条 
//             $("#jinduzzjg").find('.bar').css({ 
//                 "width": file.percent + "%" 
//             }).find(".percent").text(file.percent + "%"); 
        },
        FileUploaded: function(up, file, info) { //文件上传成功的时候触发 
            var data = JSON.parse(info.response);
            //$("#idyyzz").html("<div class='img'><img src='" + data.pic + "'/></div><p>" + data.name + "</p>");
            $("#ididxing").attr('src',data.pic);
            $("input[name='idcard_f_photo']").val(data.pic);
        },
        Error: function(up, err) { //上传出错的时候触发 

            jeBox.msg(err.message, {icon: 6});
            $("input[name='idcard_f_photo']").val(0);
        }
    }
});
idxing.init();

//新增工单end


//上传头像s

var idzavatar = new plupload.Uploader({ //创建实例的构造方法 
    runtimes: 'html5,flash,silverlight,html4',
    //上传插件初始化选用那种方式的优先级顺序 
    browse_button: 'idzavatar',
    // 上传按钮 
    url: "/userdata/uploadimgtfs",
    //远程上传地址 
    flash_swf_url: '/css/user/Moxie.swf',
    //flash文件地址 
    silverlight_xap_url: '/css/user/Moxie.xap',
    //silverlight文件地址 
    filters: {
        max_file_size: '10240kb',
        //最大上传文件大小（格式100b, 10kb, 10mb, 1gb） 
        mime_types: [ //允许文件上传类型 
            {
                title: "files",
                extensions: "jpg,png,gif"
            }]
    },
    multi_selection: true,
    true:false,//多文件上传, false 单文件上传 ctrl 多文件
    init: {
        FilesAdded: function(up, files) { //文件上传前 
            if ($("#ul_pics").children("li").length > 30) {
                jeBox.msg('您上传的图片太多了！！', {icon: 6});
                uploader.destroy();
            } else {
                var li = '';
                plupload.each(files,
                    function(file) { //遍历文件 
                        li += "<li id='" + file['id'] + "'><div class='progress'><span class='bar'></span><span class='percent'>0%</span></div></li>";
                    });
                $("#ul_pics").append(li);
                idzavatar.start();
            }
        },
        UploadProgress: function(up, file) { //上传中，显示进度条 
//             $("#jinduzzjg").find('.bar').css({ 
//                 "width": file.percent + "%" 
//             }).find(".percent").text(file.percent + "%"); 
        },
        FileUploaded: function(up, file, info) { //文件上传成功的时候触发 
            var data = JSON.parse(info.response);
            //$("#idyyzz").html("<div class='img'><img src='" + data.pic + "'/></div><p>" + data.name + "</p>");
            $("#ididzavatar").attr('src',data.pic);
            //$("input[name='idcard_f_photo']").val(data.pic);
        },
        Error: function(up, err) { //上传出错的时候触发 

            jeBox.msg(err.message, {icon: 6});
            //$("input[name='idcard_f_photo']").val(0);
        }
    }
});
idzavatar.init();
//上传头像end

//上传证明图片s

var idzavatar2 = new plupload.Uploader({ //创建实例的构造方法 
    runtimes: 'html5,flash,silverlight,html4',
    //上传插件初始化选用那种方式的优先级顺序 
    browse_button: 'idzavatar2',
    // 上传按钮 
    url: "/userdata/uploadimg",
    //远程上传地址 
    flash_swf_url: '/css/user/Moxie.swf',
    //flash文件地址 
    silverlight_xap_url: '/css/user/Moxie.xap',
    //silverlight文件地址 
    filters: {
        max_file_size: '10240kb',
        //最大上传文件大小（格式100b, 10kb, 10mb, 1gb） 
        mime_types: [ //允许文件上传类型 
            {
                title: "files",
                extensions: "jpg,png,gif"
            }]
    },
    multi_selection: true,
    true:false,//多文件上传, false 单文件上传 ctrl 多文件
    init: {
        FilesAdded: function(up, files) { //文件上传前 
            if ($("#ul_pics").children("li").length > 30) {
                jeBox.msg('您上传的图片太多了！！', {icon: 6});
                uploader.destroy();
            } else {
                var li = '';
                plupload.each(files,
                    function(file) { //遍历文件 
                        li += "<li id='" + file['id'] + "'><div class='progress'><span class='bar'></span><span class='percent'>0%</span></div></li>";
                    });
                $("#ul_pics").append(li);
                idzavatar2.start();
            }
        },
        UploadProgress: function(up, file) { //上传中，显示进度条 
//             $("#jinduzzjg").find('.bar').css({ 
//                 "width": file.percent + "%" 
//             }).find(".percent").text(file.percent + "%"); 
        },
        FileUploaded: function(up, file, info) { //文件上传成功的时候触发 
            var data = JSON.parse(info.response);
            var html = '<div class="po">' + '<span class="pox">x</span><a target="_blank" href="'+data.pic+'">' + '<img style="width:160px;height: 160px;" src="'+data.pic+'">' + '</a><input type="hidden" name="picture[]" value="'+data.pic+'"></div>';
            $('.images').append(html);
        },
        Error: function(up, err) { //上传出错的时候触发 

            jeBox.msg(err.message, {icon: 6});
            //$("input[name='idcard_f_photo']").val(0);
        }
    }
});
idzavatar2.init();
//上传证明图片end


     function font(){
         if($('input[name=enterprise_name]').val() == ''){
             jeBox.msg('请输入公司名称！！', {icon: 6,time:1});
             return false;
         }else{
             var enterprise_name = $('input[name=enterprise_name]').val();
             if(enterprise_name.length < 6 || enterprise_name.length > 25){
                 jeBox.msg('公司名称最少6个字最多25个字！！', {icon: 6,time:1});
                 return false;
             }
         }

         if($('input[name=corporation]').val() == ''){
             jeBox.msg('请输入法人姓名！！', {icon: 6,time:1});
             return false;
         }else{
             var res = checkChineseName($('input[name=corporation]').val());
             if(res == 2){
                 jeBox.msg('法人姓名最少两个汉字最多10个汉字！', {icon: 6,time:1});
                 return false;
             }
         }

         if($('input[name=corporation_code]').val() == ''){
             jeBox.msg('请输入法人身份证号码！！', {icon: 6,time:1});
             return false;
         }

         if($('input[name=business_licence]').val() == ''){
             jeBox.msg('请输入营业执照！！', {icon: 6,time:1});
             return false;
         }else{
                if(!isbucode($('input[name=business_licence]').val())){
                 jeBox.msg('营业执照格式不正确！！', {icon: 6,time:1});
                return false;
             }
         }

         if($('.province').val() == ''){
             jeBox.msg('请选择省份！！', {icon: 6,time:1});
             return false;
         }

         if($('.city').val() == ''){
             jeBox.msg('请选择城市！！', {icon: 6,time:1});
             return false;
         }

         if($('.district').val() == 0){
             jeBox.msg('请选择县区！！', {icon: 6,time:1});
             return false;
         }

         if($('input[name=address]').val() == ''){
             jeBox.msg('请输入详细地址！！', {icon: 6,time:1});
             return false;
         }else{
            var address = $('input[name=address]').val();
             if(address.length < 3 || address.length > 25){
                 jeBox.msg('详细地址最少3个字最多25个字！！', {icon: 6,time:1});
                 return false;
             }
         }

         if($('input[name=telephone]').val() == ''){
             jeBox.msg('请输入公司电话！！', {icon: 6,time:1});
             return false;
         }else{
             var tel = $('input[name=telephone]').val();
             var res = checkTel(tel);
                 if(!res){
                     jeBox.msg('公司电话格式错误！！', {icon: 6,time:1});
                     return false;
                 }
         }

         if($('input[name=manage_person]').val() == ''){
             jeBox.msg('请输入经营负责人姓名！！', {icon: 6,time:1});
             return false;
         }else{
             var res = checkChineseName($('input[name=manage_person]').val());
             if(res == 2){
                 jeBox.msg('经营负责人姓名最少2个汉字最多10个汉字！', {icon: 6,time:1});
                 return false;
             }
         }

         if($('input[name=primary_business]').val() == ''){
             jeBox.msg('请输入主营业务！！', {icon: 6,time:1});
             return false;
         }else{
             var address = $('input[name=primary_business]').val();
             if(address.length < 2 || address.length > 30){
                 jeBox.msg('主营业务最少两个字！！', {icon: 6,time:1});
                 return false;
             }
         }

         if($('input[name=enterprise_type]').val() == 0){
             jeBox.msg('请选择企业类型！！', {icon: 6,time:1});
             return false;
         }

        if($('#is_hangye').val() == 0){
            jeBox.msg('请选择完整的企业行业！！', {icon: 6,time:1});
            return false;
        }

        if($('input[name=is_three_certificate]').val() == 1){
            if($('input[name=licence_Photo2]').val() == 0){
                jeBox.msg('请上传营业执照副本照片！！', {icon: 6,time:1});
                return false;
            }

            if($('input[name=corporation_f_code2]').val() == 0){
                jeBox.msg('请上传法人身份证正面照片！！', {icon: 6,time:1});
                return false;
            }

            if($('input[name=corporation_b_code2]').val() == 0){
                jeBox.msg('请上传法人身份证反面照片！！', {icon: 6,time:1});
                return false;
            }

            if($('input[name=corporation_h_code2]').val() == 0){
                jeBox.msg('请上传法人手持身份证照片！！', {icon: 6,time:1});
                return false;
            }
        }else if($('input[name=is_three_certificate]').val() == 0){
            if($('input[name=licence_Photo]').val() == 0){
                jeBox.msg('请上传营业执照副本照片！！', {icon: 6,time:1});
                return false;
            }

            if($('input[name=organization_photo]').val() == 0){
                jeBox.msg('请上传组织机构代码证照片！！', {icon: 6,time:1});
                return false;
            }

            if($('input[name=tax_certificate_photo]').val() == 0){
                jeBox.msg('请上传税务登记证照片！！', {icon: 6,time:1});
                return false;
            }

            if($('input[name=corporation_f_code]').val() == 0){
                jeBox.msg('请上传法人身份证正面照片！！', {icon: 6,time:1});
                return false;
            }

            if($('input[name=corporation_b_code]').val() == 0){
                jeBox.msg('请上传法人身份证反面照片！！', {icon: 6,time:1});
                return false;
            }

            if($('input[name=corporation_h_code]').val() == 0){
                jeBox.msg('请上传法人手持身份证照片！！', {icon: 6,time:1});
                return false;
            }
        }
            $.ajax({
                url:"/userdata/Authenterprises",
                type:"post",
                data:$("#tf").serialize(),
                dataType: "json",
                success:function(data){
                    $("input[name=__token__]").val(data.token);
                    if(data.code == 1){
                        //成功
                        //Lobibox.notify('success',{msg:'保存成功！',position: "bottom middle",delay: 2000,width: 400});
                        jeBox.msg('提交成功！！', {icon: 7,time:1});
                        //提交成功。页面进行跳转
                        setTimeout(function(){
                             window.location.reload();
                         },1000);
                    }else if(data.code == 1000){
                        //从新登陆
                        setTimeout(function(){
                             window.location.reload();
                         },1000);
                    }else{
                        //Lobibox.notify('error',{msg:data.msg,position: "bottom middle",delay: 2000,width: 400});
                        jeBox.msg(data.msg, {icon: 6,time:1});
                    }

                },
                error:function(e){
                   //Lobibox.notify('error',{msg:'错误！',position: "bottom middle",delay: 2000,width: 400});
                    jeBox.msg('错误！！', {icon: 6,time:1});

                }
            });
        }

        //列表下拉
    $('.slist_title').on('click',function(){
        if($(this).parent().hasClass('slist_')){
                $(this).parent().removeClass('slist_')
                }else{
                    $(this).parent().addClass('slist_')
                    }   
        })
    
    $(document).on('click','.slist li',function(){
        $(this).parent().parent().removeClass('slist_').find('.slist_title').text($(this).text())
    });
    //按钮
    $('.no_dui').click(function(){
                $(this).parent().find('.no_dui').removeClass('dui');
                $(this).addClass('dui');
                if($("input[name='is_three_certificate']").val() == 0){
                    $("input[name='is_three_certificate']").val(1);
                }else{
                    $("input[name='is_three_certificate']").val(0);
                }
        })

    //按钮点击切换
    
    $('.tog_dui .no_dui').click(function(){
        var n=$('.no_dui').index(this);
                $(this).parent().find('.no_dui').removeClass('dui');
                $(this).addClass('dui')
                $('.tog').hide().eq(n).show();
        })

function checkChineseName(v) {
    if (v == '') return 1; if (v.length < 2) { return 2; }
    if (v.length > 10) { return 2; }
    var name = v.replace(/·/g, ''); name = name.replace(/•/g, '');
    if(checkChinese(name))  return 0; else return 2;
};

function checkChinese(str) { var re = /[^\u4e00-\u9fa5]/; if (re.test(str)) return false; return true; };

function isbucode(busCode) {
    if(busCode == ''){
        return false;
    }
    var ret= '';
    if(busCode.length > 5 && busCode.length < 19){
        for(var i=1;i<busCode.length+1;i++){
            if(busCode.length != i){
                var p = /^[0-9]*$/;
                var b = p.test(busCode[i-1]);
                if(!b){
                    ret += i+'.';
                }
            }else{
                //最后一位可以是数字和字母
                var p = /^[A-Za-z0-9]*$/;
                var b = p.test(busCode[i-1]);
                if(!b){
                    ret += i+'.';
                }
            }
        }
    }else{
        return false;
    }
    if(ret.length > 0){
        return false;
    }
    return true;
}

String.prototype.Trim = function() {
    var m = this.match(/^\s*(\S+(\s+\S+)*)\s*$/);
    return (m == null) ? "" : m[1];
}
String.prototype.isMobile = function() {
    return (/^(((13[0-9]{1})|(15[0-9]{1})|(17[0-9]{1})|(18[0-9]{1}))+\d{8})$/.test(this.Trim()));
}
String.prototype.isTel = function()
{
//"兼容格式: 国家代码(2到3位)-区号(2到3位)-电话号码(7到8位)-分机号(3位)"
//return (/^(([0\+]\d{2,3}-)?(0\d{2,3})-)?(\d{7,8})(-(\d{3,}))?$/.test(this.Trim()));
    return (/^(([0\+]\d{2,3}-)?(0\d{2,3})-)(\d{7,8})(-(\d{3,}))?$/.test(this.Trim()));
}
function checkTel(value) {
        if (value.isMobile() || value.isTel()) {
            return true;
        }
        else {
            return false;
        }
}
