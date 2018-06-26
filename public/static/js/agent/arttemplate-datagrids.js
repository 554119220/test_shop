/**
 * Created by Administrator on 2017/9/13.
 */
function init(options, obj) {
    function getParam() {
        var param = "p=" + opts.pageIndex + "&pagesize=" + opts.pageSize;
        param = param + "&" + opts.queryParams;
        console.log(param);
        return param;
    }
    function queryForm() {
        var cells = document.getElementById(opts.table).rows.item(0).cells.length; //table列数
        if (opts.isPagination) {
            document.getElementById(opts.pagination).innerHTML = "";
        }
        var trStr = "<tr><td colspan=" + cells + " style='text-align:center'>{0}</td></tr>";
        obj.html(trStr.replace("{0}", "<img src='/images/agent/loading.gif'/>数据正在加载中..."));
        var url = opts.url + "?ts=" + Math.random();
        $.post(url, getParam(), function (result) {
            if (result.data.list.length == 0 || typeof (result.data.list.length) == "undefined") {
                obj.html(trStr.replace("{0}", "没有查询到您想要的数据"));
                return;
            }
            data.list = result.data.list;
            var html = template(opts.scriptHtml, data);
            obj.html(html);
            if (result.data.pageinfo.count > 0 && opts.isPagination) {
                totalCount = result.data.pageinfo.count;
                pageInitialize(opts.pagination, opts.pageIndex, opts.pageSize, totalCount);
            }
            callbackFun();
        });
    }

    function pageInitialize(pageID, pageIndex, pageSize, totalCount) {
        laypage({
            cont: pageID, //容器
            pages: Math.ceil(totalCount / pageSize), //总页数
            curr: pageIndex, //初始化当前页
            jump: function (e, first) { //触发分页后的回调
                opts.pageIndex = e.curr;
                if (!first) { //一定要加此判断，否则初始时会无限刷新
                    queryForm();
                }
            }
        });
    }

    function callbackFun() {
        if (opts.onLoadSuccess != null) {
            opts.onLoadSuccess();
        }
    }
    //默认值
    var defaults = {
        pageSize: 10,
        pageIndex: 1,
        queryParams: "",
        pagination: "pagination",  //分页id
        scriptHtml: "eTableHtml",   //scriptHtml的id
        table: "eTableRow",         //table的id
        url: "",
        isPagination: false,
        onLoadSuccess: null,
    }

    var opts = $.extend(defaults, options);
    var data = new Array();
    var totalCount;
    queryForm();

    var method = {};
    return method.getPageIndex = function () {
        return this.pageIndex;
    },//当前页刷新
        method.onReload = function () {
            queryForm();
        },//重新加载
        method.onLoad = function () {
            opts.pageIndex = 0;
            queryForm();
        },
        method.getData = function () {
            return data;
        },
        method.getTotalCount = function () {
            return totalCount;
        },
        method
}

$.fn.datagrid = function (options) {
    return init(options, $(this));
}