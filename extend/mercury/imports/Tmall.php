<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/6 0006
 * Time: 17:46
 */

namespace mercury\imports;


class Tmall extends Imports
{
    public function __construct($url)
    {
        parent::__construct($url);
    }


    public function parseHtml()
    {
//        $html   = $this->getHtmlDocument();
        echo($this->html);
    }

    public function getTitle()
    {
        $pattern    = '#<div class="tb-detail-hd">(.*)</div>#';
        preg_match($pattern, $this->html, $match);
        echo $match;
    }

    public function getSubTitle()
    {
        
    }

    public function getSkus()
    {
        
    }

    public function getPrice()
    {
        
    }

    public function getImages()
    {
        
    }
}