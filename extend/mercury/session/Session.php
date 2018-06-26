<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/29 0029
 * Time: 16:18
 */

namespace mercury\session;

use traits\think\Instance;
use app\common\traits\F;
/**
 * Class Session
 * @package mercury\session
 *
 * 设置session
 */
class Session
{
    use Instance;
    /**
     * 需要设置的api
     */
    const API_URI   = [
        // '\app\api\controller\user\v1\Login\index',
        '\app\api\controller\user\v1\Login\erp',
        // '\app\api\controller\user\v1\Register\index'
    ];

    protected $require_set = false;

    public function __construct($controller, $action)
    {
        $action = "{$controller}\\{$action}";
        if (in_array($action, self::API_URI)) $this->require_set = true;
    }

    /**
     * 设置session
     *
     * @param array $data
     */
    public function setSession(array $data)
    {
        if ($this->require_set) {
            session('user', $data);
        }
    }
}