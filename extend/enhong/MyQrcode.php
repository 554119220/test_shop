<?php
/**
 * 二维码生成
 */
namespace enhong;
vendor('phpqrcode.phpqrcode');
class MyQrcode
{
    static function create_qrcode($url,$outfile,$size = 4,$level = 'L',$margin = 2){
        \phpqrcode\QRcode::png($url, $outfile, $level, $size ,$margin);
    }


}

