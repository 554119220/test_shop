<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/29 0029
 * Time: 9:16
 */

namespace app\api\controller\goods\v1;

use app\api\model\goods\GoodsSku;
use app\api\model\goods\UserHistory;
use app\common\traits\F;
use mercury\constants\Code;
use mercury\ResponseException;

/**
 * Class History
 * @package app\api\controller\user\v1
 *
 * 浏览历史, 需要加入权重算法
 */
class History
{
    /**
     * @var object UserHistory
     * @var array $map
     */
    protected $model, $map = [];
    public function __construct()
    {
        $this->model    = new UserHistory();
        $this->map['user_id']   = request()->user['user_id'];
    }
    /**
     * 收藏列表
     *
     * @param int $user_id
     * @return array
     */
    public function index()
    {
        try {
            $data   = F::dataList($this->model, [
                'where' => $this->map,
                'order' => 'user_history_day desc'
            ]);
            if (!$data) throw new ResponseException(Code::CODE_NO_CONTENT);
            return $data;
        } catch (ResponseException $e) {
            return $e->getData();
        }
    }

    /**
     * 收藏
     *
     * @param int $user_ud
     * @param int $sku_id
     * @return array|int
     */
    public function create()
    {
        try {
            $model  = new GoodsSku();
            $goods  = $model->relation('goods')->where('goods_sku_id', request()->data['sku_id'])->find();
            if (!$goods) throw new ResponseException(Code::CODE_OTHER_FAIL, '商品不存在');

            #   判断买家是否已浏览
            $this->map['goods_id']  = $goods['goods_id'];
            $user_history_id    = $this->model->where($this->map)->column('user_history_id');
            if ($user_history_id) return Code::CODE_SUCCESS;

            $data   = [
                'goods_id'      => $goods['goods_id'],
                'goods_sku_id'  => request()->data['sku_id'],
                'user_id'       => $this->map['user_id'],
            ];
            $flag   = $this->model->insert($data);
            if (false == $flag) throw new ResponseException(Code::CODE_OTHER_FAIL, '收藏失败，请稍后重试');
            return Code::CODE_SUCCESS;
        } catch (ResponseException $e) {
            return $e->getData();
        }
    }

    /**
     * 删除收藏
     *
     * @param int $user_id
     * @param int $id
     * @return array|int
     */
    public function delete()
    {
        try {
            $this->map['user_history_id']   = request()->data['id'];
            $flag   = $this->model->where($this->map)->delete();
            if (false == $flag) throw new ResponseException(Code::CODE_OTHER_FAIL, '删除失败，请稍后重试');
            return Code::CODE_SUCCESS;
        } catch (ResponseException $e) {
            return $e->getData();
        }
    }
}