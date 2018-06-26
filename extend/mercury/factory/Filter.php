<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/11 0011
 * Time: 14:34
 */

namespace mercury\factory;


use app\common\traits\F;
use mercury\constants\Cache;
use mercury\constants\State;

class Filter
{
    protected $data, $controller;
    protected static $instance;
    const NOT_FILTER_CONTROLLER = [
        '/orders/v1/notify/pay',
        '/ads/v1/ads/pay',
    ];
    public function __construct($data, $controller)
    {
        $this->controller   = $controller;
        if (is_array($data)) $data = serialize(array_values($data));
        $this->data = $data;
    }

    /**
     * 检测禁用关键字
     *
     * @return bool
     */
    public function check()
    {
        if (in_array($this->controller, self::NOT_FILTER_CONTROLLER)) return true;
        $pattern    = "/({$this->getFilterWords()})/";
        preg_match_all($pattern, $this->data, $match);
        if (isset($match[0]) && !empty($match[0])) {
            return implode(',', $match[0]);
        }
        return true;
    }

    /**
     * 获取单例
     *
     * @param $data
     * @return static
     */
    public static function instance($data, $controller)
    {
        return new static($data, $controller);
    }

    /**
     * 获取关键字
     *
     * @return mixed|string
     */
    public function getFilterWords()
    {
        #   disabled_keyword    通用
        $key    = F::getCacheName(Cache::DISABLED_KEYWORD_DETAIL . 0);
        $words  = F::redis()->get($key);
        if (!$words) {
            $data  = db('disabled_keyword')->where(['status' => State::STATE_NORMAL, 'category_id' => State::STATE_DISABLED])->column('keyword');
            if (!empty($data)) {
                foreach ($data as $v) {
                    $words .= str_replace([',', '、', ' ', '/','（', '）',"\r\n", "\r", "\t", "\n"],',',$v);
                }
                $words    = implode('|', array_filter(array_unique(explode(',', $words))));
                F::redis()->set($key, $words);
            }
        }
        return $words;
            //'cwit|zrst|百望|百望富通|百望商城|中睿|中睿盛通|中睿商城|百分百|大唐|大唐天下|云联惠|全返|小唐|云连汇|批发|dttx|trj|等值|等额|积分赠送|返积分|专供|特供|招商|代理|微商|返利|积分|会员|天猫|淘宝|京东虚假|史无前例|前无古人|永久|万能|祖传|特效|无敌|纯天然|100％|高档|正品|真皮|超赚|精确|权威|老字号|中国驰名|特供|专供|专家推荐|质量免检|无需国家质量检测|免抽检|领导人推荐|机关推荐|人民币图样最|最佳|最具|最爱|最赚|最优|最优秀|最好|最大|最大程度|最高|最高级|最高端|最奢侈|最低|最低级|最低价|最底|最便宜|史上最低价|最流行|最受欢迎|最时尚|最聚拢|最符合|最舒适|最先|最先进|最先进科学|最先进加工工艺|最先享受|最后|最后一波|最新|最新技术|最新科学|第一|中国第一|全网第一|销量第一|排名第一|唯一|第一品牌|NO\.1|TOP\.1|独一无二|全国第一|一流|一天|仅此一次一款|最后一波|全国十大品牌之一|国家级|国家级产品|全球级|宇宙级|世界级|顶级|顶尖|尖端|顶级工艺|顶级享受|高级|极品|极佳|绝佳|绝对|终极|极致|首个|首选|独家|独家配方|首发|全网首发|全国首发|首家|全网首家|全国首家|全网独家|全网首发|首次|首款|全国销量冠军|国家级产品|国家|国家免检|国家领导人|填补国内空白|中国驰名|国际品质|欺诈|涉嫌欺诈消费者|点击领奖|恭喜获奖|全民免单|点击有惊喜|点击获取|点击转身|点击试穿|点击翻转|领取奖品|涉嫌诱导消费者|秒杀|抢爆|再不抢就没了|不会再便宜了|没有他就|错过就没机会了|万人疯抢|全民疯抢/抢购|卖/抢疯了';
    }
}