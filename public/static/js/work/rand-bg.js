//���������ȡ��Ԫ
function randomSort(arr)
{
    if(!arr || !arr.length)
    {
		return [];
    }
    var outputArr = [];
    var cloneInputArr = arr.slice(0,arr.length);
    while( cloneInputArr.length)
	{
		outputArr.push(cloneInputArr.splice(Math.random() * cloneInputArr.length,1)[0]);
	}
	return outputArr;
}
var RandBG = function () {
	var pic=new Array();
		pic[0]='/static/images/work/login-bg/1.jpg';
		pic[1]='/static/images/work/login-bg/2.jpg';
		pic[2]='/static/images/work/login-bg/3.jpg';
		pic[3]='/static/images/work/login-bg/4.jpg';
		pic[4]='/static/images/work/login-bg/5.jpg';
		//alert(randomSort(pic));
	var rand_pic=randomSort(pic);

    return {
        //main function to initiate the module
        init: function () {

            $.backstretch(rand_pic, {
    		          fade: 1000,
    		          duration: 10000
    		    });

        }

    };

}();

RandBG.init();

