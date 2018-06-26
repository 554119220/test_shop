<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/2/26 0026
 * Time: 16:35
 */

namespace mercury\common;

use app\common\traits\F;
use Fukuball\Jieba\Finalseg;
use Fukuball\Jieba\Jieba;

/**
 * Class Seo
 * @package mercury\common
 * @title Seo
 */
class Seo
{

    protected $title, $keywords, $description;

    public function __construct($title, $keywords = '', $description = '')
    {
        $this->title    = $title ? "{$title} - " . config('site.site')['name'] : config('site.site')['name'];
        $this->keywords = $keywords ? : config('site.seo')['keywords'];
        $this->description  = $description ? : config('site.seo')['description'];
    }

    /**
     * @title instance
     * @param $title
     * @param string $keywords
     * @param string $description
     * @return static
     */
    public static function instance($title, $keywords = '', $description = '')
    {
        return new static($title, $keywords, $description);
    }

    /**
     * @title 获取SEO信息
     * @return array
     */
    public function getSeo()
    {
        return [
            'title'         => $this->title,
            'keywords'      => $this->getParseKeywords(),
            'description'   => $this->getParseDescription()
        ];
    }

    /**
     * @title 解析关键字
     * @return mixed
     */
    protected function getParseKeywords()
    {
        return $this->participle($this->keywords);
    }

    /**
     * @title 解析页面描述
     * @return mixed
     */
    protected function getParseDescription()
    {
        return $this->participle($this->description);
    }

    /**
     * @title 分词
     * @param $string
     * @return mixed
     */
    protected function participle($string)
    {
        return $string;
        Jieba::init();
        Finalseg::init();
        return implode(',', Jieba::cut($string, false));
    }

}