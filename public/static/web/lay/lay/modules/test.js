/**
 * Created by Administrator on 2018/1/27 0027.
 */
layui.define(function(exports){ //提示：模块也可以依赖其它模块，如：layui.define('layer', callback);
    var obj = {
        hello: function(str){
            alert('Hello '+ (str||'mymod'));
        }
    };

    //输出test接口
    exports('mymod', obj);
});