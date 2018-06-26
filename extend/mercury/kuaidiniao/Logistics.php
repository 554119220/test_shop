<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/6 0006
 * Time: 17:22
 */

namespace mercury\kuaidiniao;
use mercury\ResponseException;

/**
 * Class Logistics
 * @package mercury\kuaidiniao
 *
 * 物流查询
 */
class Logistics extends KDniao
{
    /**
     * @var string $com 快递公司编码
     * @var string $code 快递单号
     */
    protected $com, $code;
    public function __construct(array $config = [])
    {
        parent::__construct($config);
    }

    /**
     * 即时查询
     *
     * @return string
     */
    public function query()
    {
        $data   = [
            'EBusinessID'   => $this->config['app_id'],
            'RequestType'   => 8001,
            'RequestData'   => $this->getRequestJson(),
            'DataType'      => 2,
            'DataSign'      => $this->encrypt()
        ];
        $response   = $this->request('EbusinessOrderHandle.aspx', $data);
        return $this->parseResponse($response);
    }


    /**
     * 物流更新
     *
     * @return string
     */
    public function track()
    {
        $data   = [];
        $response   =  $this->request('EbusinessOrderHandle.aspx', $data);
        return $this->parseResponse($response);
    }

    /**
     * 设置快递公司
     *
     * @param $com
     * @return $this
     */
    public function setCompany($com)
    {
        $this->com  = $com;
        return $this;
    }

    /**
     * 设置快递单号
     *
     * @param $code
     * @return $this
     */
    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

    /**
     * 解析response数据
     *
     * @param $response
     * @return array
     */
    private function parseResponse($response)
    {
        $ret    = [
            'code'  => 0,
        ];
        if ($response['Success'] == true && !empty($response['Traces'])) {
            foreach ($response['Traces'] as $k => $v) {
                $ret['data'][$k]['time']    = $v['AcceptTime'];
                $ret['data'][$k]['text']    = $v['AcceptStation'];
            }
            $ret['code']    = 1;
        } else {
            $ret['msg']     = $response['Reason'];
        }
        return $ret;
    }
}