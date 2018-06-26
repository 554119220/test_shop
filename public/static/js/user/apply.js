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
                jeBox.msg('您上传的图片太多了！', {icon: 6}); 
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
            $("input[name='panorama']").val(data.pic); 
        }, 
        Error: function(up, err) { //上传出错的时候触发 
            jeBox.msg(err.message, {icon: 6});
            $("input[name='panorama']").val(0); 
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
                  jeBox.msg('您上传的图片太多了！', {icon: 6});
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
            $("input[name='productp_hotos']").val(data.pic);
        }, 
        Error: function(up, err) { //上传出错的时候触发 
            jeBox.msg(err.message, {icon: 6});
            $("input[name='productp_hotos']").val(0); 
        } 
    } 
}); 
zzjg.init();

var idz2 = new plupload.Uploader({ //创建实例的构造方法 
    runtimes: 'html5,flash,silverlight,html4', 
    //上传插件初始化选用那种方式的优先级顺序 
    browse_button: 'idz2', 
    // 上传按钮 
    url: "/userdata/uploadimg", 
    //远程上传地址 
    flash_swf_url: '../css/user/Moxie.swf', 
    //flash文件地址 
    silverlight_xap_url: '../css/user/Moxie.xap', 
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
                jeBox.msg('您上传的图片太多了！', {icon: 6}); 
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
            $("input[name='shopwindow']").val(data.pic);
        }, 
        Error: function(up, err) { //上传出错的时候触发 
            jeBox.msg(err.message, {icon: 6});
            $("input[name='shopwindow']").val(0);
        } 
    } 
}); 
idz2.init();

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
                jeBox.msg('您上传的图片太多了！', {icon: 6}); 
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
            $("input[name='outdoorscene']").val(data.pic); 
        }, 
        Error: function(up, err) { //上传出错的时候触发 
            jeBox.msg(err.message, {icon: 6});
            $("input[name='outdoorscene']").val(0);
        } 
    } 
}); 
idf.init();