/**
 * Created by Administrator on 2017/10/27 0027.
 */
/**
 * api post
 *
 * @param data
 * @param header
 */
function api(data, header) {
    $.ajax({
        url:'/api',
        data:data,
        type:'POST',
        headers:header,
        success:function (ret) {
            console.log(ret);
            if (ret.code != 20000) {

            } else {

            }
        },
        error:function (ret) {
            
        }
    });
}