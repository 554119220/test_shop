/**
 * 计算库存积分 金额*100*倍数
 * @param obj
 */
function get_stock(obj){
    var grade = obj.options[obj.selectedIndex].value;
    //计算库存积分 金额*100*倍数
    var stock = Number(grade) * Number($('#money').val()) * Number(100);

    $('.update').html(stock.toFixed(2));
}