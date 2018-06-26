<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/4 0004
 * Time: 17:42
 */

namespace app\api\service\user\v1;

use think\Model;

/**
 * Class Login
 * @package app\api\service\user\v1
 *
 * 服务层 提供数据接口
 */
class Login extends Model
{
    public function index()
    {
        
    }

    public function register($param)
    {
        return $this->insert($param);
    }
}