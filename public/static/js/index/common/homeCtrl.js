//头部滚动
$(document).scroll(function(){
    viewH =$(this).height();//可见高度
    contentH =$(this).get(0).scrollHeight;//内容高度
    scrollTop =$(this).scrollTop();//滚动高度
    if(scrollTop >= 500) {
        //滚动高度大于等于500的时候执行
        $(".headtop").addClass('heads')
    }else{
        $(".headtop").removeClass('heads')
    }

});