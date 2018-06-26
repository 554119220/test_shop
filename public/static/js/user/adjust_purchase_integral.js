
$(document).on('click','.slist_title',function(){
    if($(this).parent().hasClass('slist_')){
        $(this).parent().removeClass('slist_')
    }else{
        $(this).parent().addClass('slist_')
    }

})

$(document).on('click','.slist li',function(){
    $(this).parent().parent().removeClass('slist_').find('.slist_title').text($(this).text())
});
$(document).on('click','.pox',function(){
    $(this).parent().remove();
});
$('.submit-as').click(function () {
    submit2('tf','/transaction/adjust','/transaction/adjust_purchase_integral_list','提交成功，等待审核！');
});