<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/10 0010
 * Time: 17:05
 */

namespace app\api\model\tools;


use app\common\traits\F;
use mercury\constants\Cache;
use mercury\constants\Code;
use mercury\ResponseException;
use think\Model;

/**
 * Class District
 * @package app\api\model
 *
 * 地区模型
 */
class District extends Model
{
    protected $table   = 'zr_area';
    protected $resultSetType = 'array';
    protected $pk = 'id';
    protected $append = [];
    protected $autoWriteTimestamp = 'datetime';
    protected $createTime = 'create_time';
    protected $updateTime = 'update_time';
    protected $dateFormat = 'Y-m-d H:i:s';
    protected $auto = [];
    protected $insert = [];
    protected $update = [];

    /**
     * 获取地址详情
     *
     * @param $id
     * @return array|mixed
     */
    public function getDistrictInfo($id)
    {
        try {
            $key    = F::getCacheName(Cache::DISTRICT_ROW_CACHE . $id);
            $data   = F::redis()->get($key);
            if (!$data) {
                $data   = F::dataDetail($this, [
                    'where' => ['id' => $id]
                ]);
                if (!$data) throw new ResponseException(Code::CODE_NO_CONTENT);
                $data   = serialize($data);
                F::redis()->set($key, $data);
            }
            if ($data) $data = unserialize($data);
            return (array) $data;
        } catch (ResponseException $e) {
            return $e->getData();
        }
    }
}