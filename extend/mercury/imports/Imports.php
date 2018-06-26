<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/6 0006
 * Time: 17:45
 */

namespace mercury\imports;


abstract class Imports
{
    protected $url, $html;

    public function __construct($url)
    {
        $this->url  = $url;
        $this->html = $this->getHtmlDocument();
    }

    /**
     * @title getHtmlDocument
     * @return bool|string
     */
    public function getHtmlDocument()
    {
        return mb_convert_encoding(file_get_contents($this->url), 'UTF-8', 'GBK');
    }

    abstract public function parseHtml();
    
    abstract public function getTitle();
    
    abstract public function getSubTitle();

    abstract public function getPrice();

    abstract public function getSkus();

    abstract public function getImages();
}