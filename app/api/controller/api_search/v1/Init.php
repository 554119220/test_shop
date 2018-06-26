<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/13 0013
 * Time: 10:28
 */

namespace app\api\controller\search\v1;


use app\common\traits\F;
use mercury\constants\State;

class Init
{
    protected $data = [], $user = [], $index, $params = [];
    #   每页多少行记录
    const ROWS_SIZE = 20;
    const ROWS_MAX_SIZE = 100;
    public function __construct()
    {
        $this->data = request()->data;
        $this->user = request()->user;
    }

    /**
     * 解析搜索返回数据
     *
     * @param array $data
     * @return mixed
     */
    protected function parseData(array $data)
    {
        $ret['total']       = $data['total'];
        $ret['current_page']= isset($this->data['p']) ? (int) $this->data['p'] : 1;
        $rows   = isset($this->data['rows']) ?
            (int) ($this->data['rows'] > self::ROWS_MAX_SIZE ?  self::ROWS_MAX_SIZE : $this->data['rows']) :
            self::ROWS_SIZE;
        $ret['last_page']   = (int) ceil($ret['total'] / $rows);
        $ret['data']    = [];
        $imagesDomain   = F::getImagesDomain();
        foreach ($data['hits'] as $k => $v) {
            unset($v['_source']['type'],
                $v['_source']['@version'],
                $v['_source']['@timestamp'],
                $v['_source']['goods_browse_num'],
                $v['_source']['goods_create_time'],
                $v['_source']['goods_extend_pr'],
                $v['_source']['goods_pr'],
                $v['_source']['goods_update_time'],
                $v['_source']['package_id'],
                $v['_source']['protection_id'],
                $v['_source']['seller_user_id'],
                $v['_source']['express_id'],
                $v['_source']['id']);
            $v['_source']['goods_images']   = "{$imagesDomain}{$v['_source']['goods_images']}";
            $v['_source']['shopping_score'] = number_format($v['_source']['goods_shopping_score_multi'] * $v['_source']['goods_min_price'], 2);
            $v['_source']['reward_score']   = number_format($v['_source']['goods_score_multi'] * $v['_source']['goods_min_price'], 2);
            $sku = db('goods_sku')
                ->where(['goods_id' => $v['_source']['goods_id']])
                ->order('goods_sku_price asc')
                ->limit(1)
                ->cache(600)
                ->field('goods_sku_market_price,goods_sku_id')
                ->find();
            $v['_source']['sku_id']         = $sku['goods_sku_id'];
            $v['_source']['market_price']   = $sku['goods_sku_market_price'];
            if ($v['_source']['goods_is_self'] == State::STATE_NORMAL) $v['_source']['goods_labels'][]  = '自';
            if ($v['_source']['goods_recommend_type'] == State::STATE_NORMAL) $v['_source']['goods_labels'][]  = '精';
            if ($v['_source']['goods_recommend_type'] == State::STATE_REFUNDS) $v['_source']['goods_labels'][]  = '优';

            $ret['data'][]  = $v['_source'];
        }
        return $ret;
    }

    protected function shopData(array $data)
    {
        $ret['total']       = $data['total'];
        $ret['current_page']= isset($this->data['p']) ? (int) $this->data['p'] : 1;
        $rows   = isset($this->data['rows']) ?
            (int) ($this->data['rows'] > self::ROWS_MAX_SIZE ? self::ROWS_MAX_SIZE : $this->data['rows']) :
            self::ROWS_SIZE;
        $ret['last_page']   = (int) ceil($ret['total'] / $rows);
        $ret['data']    = [];
        $imagesDomain   = F::getImagesDomain();
        foreach ($data['hits'] as $k => $v) {
            unset($v['_source']['type'],
                $v['_source']['@version'],
                $v['_source']['@timestamp'],
                $v['_source']['id'],
                $v['_source']['goods_category_level1'],
                $v['_source']['user_id'],
                $v['_source']['shop_window_num'],
                $v['_source']['shop_update_time'],
                $v['_source']['shop_type_id'],
                $v['_source']['shop_town_id'],
                $v['_source']['shop_tel'],
                $v['_source']['shop_street'],
                $v['_source']['shop_state'],
                $v['_source']['shop_qq'],
                $v['_source']['shop_province_id'],
                $v['_source']['shop_pr'],
                $v['_source']['shop_mobile'],
                $v['_source']['shop_level_score'],
                $v['_source']['shop_level'],
                $v['_source']['shop_extend_pr'],
                $v['_source']['shop_end_time'],
                $v['_source']['shop_email'],
                $v['_source']['shop_description'],
                $v['_source']['shop_create_time'],
                $v['_source']['shop_contect_person'],
                $v['_source']['shop_city_id'],
                $v['_source']['shop_browse_num'],
                $v['_source']['shop_basis_score'],
                $v['_source']['orders_wait_ship_num'],
                $v['_source']['orders_wait_receive_num'],
                $v['_source']['orders_wait_pay_num'],
                $v['_source']['orders_wait_comment_num'],
                $v['_source']['is_lock'],
                $v['_source']['goods_window_num'],
                $v['_source']['goods_wait_sales_num'],
                $v['_source']['goods_sales_num'],
                $v['_source']['goods_num'],
                $v['_source']['goods_in_sales_num'],
                $v['_source']['goods_comment_poor_num'],
                $v['_source']['goods_comment_middle_num'],
                $v['_source']['goods_comment_num'],
                $v['_source']['goods_comment_good_num'],
                $v['_source']['goods_browse_num'],
                $v['_source']['goods_category_ids']);

            $v['_source']['shop_logo']  = "{$imagesDomain}{$v['_source']['shop_logo']}";
            $searchGoods    = new Goods();
            $goods          = $searchGoods->setData(['shop_id' => $v['_source']['shop_id'], 'rows' => 3])->index()['data'] ?? [];
            $v['_source']['goods']      = $goods;
            $ret['data'][]  = $v['_source'];
        }
        return $ret;
    }

    public function setData(array $data)
    {
        $this->data = $data;
        return $this;
    }
}