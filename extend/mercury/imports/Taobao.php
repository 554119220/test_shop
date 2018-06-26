<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/6 0006
 * Time: 17:46
 */

namespace mercury\imports;


class Taobao extends Imports
{
    public function parseHtml()
    {
        $html   = $this->getHtmlDocument();
    }
}