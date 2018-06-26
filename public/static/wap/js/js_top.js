
//兼容ios首页顶部滚动渐变
function topBg(){
    var oContent = document.getElementsByClassName("content")[0];    
    oContent.onscroll = function (e) {
        var i = this.scrollTop/100
        var oBj = document.getElementsByClassName("bg_transparent")[0];
        if(oBj){
            oBj.style.backgroundColor = 'rgba(216,33,40,'+i+')'; 
        }        
    }
}

window.onload = function(){ 
	topBg();	
}


//返回顶部
var bSys=true;
var timer=null;
$('.content').scroll(function(e){
    if( $(this).scrollTop() > 100){
        $('.go_top').show()
    }else{
        $('.go_top').hide() 
    }
    if(!bSys){
        clearInterval(timer);
    }
    bSys=false;
})

$('.go_top').click(function(){
    timer=setInterval(function (){
        var iSpeed = Math.floor(-$('.content').scrollTop()/4);        
        if($('.content').scrollTop()==0){
            clearInterval(timer);
        }            
        bSys=true;
        $('.content').scrollTop( $('.content').scrollTop()+iSpeed );
    }, 30);
})

//首页改版
$(function(){
    $(document).on("click", ".set_fles >a", function (e) { //频道页面选择
        $(this).addClass('active').siblings().removeClass('active')
        $(this).parents('.set_fles').find('.fix').hide()
        $(this).parents('.set_fles').find('td').removeClass('active')
        $(this).parents('.set_fles').find('td').eq($(this).index()).addClass('active')
    })
    $(document).on("click", ".set_fles .fix span, .mask_page.modal-overlay-visible", function (e) { //关闭遮罩 关闭弹层
        $(".content").removeClass("over");
        $('.set_fles .fix').hide()
        $('.mask_page').removeClass('modal-overlay-visible')
    })

    $(document).on("click", ".set_fles >span", function (e) { //打开遮罩 打开弹层
        $(".content").addClass("over");
        $(this).siblings('.fix').show()
        $('.mask_page').addClass('modal-overlay-visible')
    })

    $(document).on("click", ".set_fles td", function (e) { //频道页面弹层内容选择
        if($(this).text()){
            if($('.set_fles td').index(this) > Number($('.set_fles >a').size()-1) ){
                $('.set_fles a').removeClass('active')
            }else{
                $('.set_fles a').eq($('.set_fles td').index(this)).addClass('active').siblings().removeClass('active')
            }
            $(this).parents('.fix').find('td').removeClass('active')
            $(this).addClass('active')
            $(this).parents('.fix').hide()
            $(".content").removeClass("over");
            $('.mask_page').removeClass('modal-overlay-visible')
        }       
    })    
})


//搜索改版
$(function(){
    $(document).on("click", ".search_set_icon a.button", function (e) { //属性选择高亮状态
        $(this).addClass('active').siblings().removeClass('active') //当前高亮状态
        $(this).siblings('.icon_tow').removeClass('icon_tow_b') //初始化价格（icon_tow）状态
        $(this).siblings().find('.ab').hide() //关闭弹窗
        $('.mask_page_bottom').removeClass('modal-overlay-visible') //关闭遮罩
        if( $(this).find('.ab').html() ){ //是否有弹窗层
            $(".content").addClass("over"); //阻止滚动
            $(this).find('.ab').show()
            $('.mask_page_bottom').addClass('modal-overlay-visible')
        } 
    })

    $(document).on("click", ".mask_page_bottom", function (e) { //关闭遮罩及弹窗
        $(".content").removeClass("over");
        $('.search_set_icon .ab').hide()
        $('.mask_page_bottom').removeClass('modal-overlay-visible')
    })

    $(document).on("click", ".search_set_icon p", function (e) { //关闭遮罩及弹窗
        event.stopPropagation();
        $(this).addClass('text_red').siblings().removeClass('text_red')
        $(".content").removeClass("over");
        $('.search_set_icon .ab').hide()
        $('.mask_page_bottom').removeClass('modal-overlay-visible')
    })

    $(document).on("click", ".panel_set .search_fast", function (e) { //侧边内选择
        $('.panel_set .search_fast').removeClass('active');
        $(this).addClass('active')
    })

    $(document).on("click", ".icon_tow", function (e) { //价格选择高低状态
        $(this).toggleClass('icon_tow_b');
    })

    $(document).on("click", ".panel_set .search_fast", function (e) { //右侧弹层选项高亮选择
        $('.panel_set .search_fast').removeClass('active');
        $(this).addClass('active')
    })    
})

