/**
 * Created by Administrator on 2018/1/22 0022.
 */
$(function () {
    $(".list-plus-a").click(function () {
        var $this   = $(this);
        var dd  = $this.closest('dl').find('dd');
        if (dd.hasClass('hide')) {
            dd.removeClass('hide');
            $this.find('.list-plus').html('－');
        } else {
            dd.addClass('hide');
            $this.find('.list-plus').html('＋');
        }
    });

    $(".on-choose dd a").click(function () {
        var router  = $(this).attr('href');
        router  = router.replace('/wiki?route=', '');
        console.log(router)
        $('.main form').find('input[name="router"]').val(router);
        $('.main form').find('input[name="router-d"]').val(router);
        return false;
    });
});