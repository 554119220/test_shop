<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/27 0027
 * Time: 10:23
 */

namespace app\common\behavior;

use app\common\traits\F;

/**
 * 用户分享码
 *
 * Class ShareCode
 * @package app\common\behavior
 */
class ShareCode
{
    public function run(&$content)
    {
        #   如果用户为登陆状态，则为每个URL加上当前用户的分享码
        if (session('user')) {
            $share_code = session('user.erpUser')['promo_code'];
            $pattern    = "/href=\"[^\"]*\"/";
            $content = preg_replace_callback($pattern, function ($match) use ($share_code) {
                if (strpos($match[0], 'css') === false
                    && strpos($match[0], 'javascript') === false
                    && strpos($match[0], 'href="#') === false
                    && strpos($match[0], 'share_code') === false
                    && strpos($match[0], 'href="tel') === false
                    ) {
                    if (strpos($match[0], '?') !== false) { 
                        #   兼容wap端的VUE
                        $params = substr($match[0], strpos($match[0], '?') + 1);
                        $share_code = "?share_code={$share_code}&{$params}";
                        $url    = substr($match[0], 0, strpos($match[0], '?'));
                    } else {
                        $share_code = "?share_code={$share_code}\"";
                        $url    = substr($match[0], 0, -1);
                    }
                    return "{$url}{$share_code}";
                }
                return $match[0];
            }, $content);
        }
        #   设置share code
        if (request()->has('share_code') && request()->param('share_code') != '') {
            cookie('SHARE_CODE', request()->param('share_code'));
        }
    }
}