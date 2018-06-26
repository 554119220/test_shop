<?php
namespace app\api\validate\ads\v1;
/**
 * Create Model from Lzy ValidateGenerate.
 * @author Lzy
 * @date 2018-03-20 15:08:33
 */
use mercury\constants\State;
use app\common\traits\F as Fun;

class Ads extends \think\Validate
{
    protected $position;
    protected $rule = [
        'ads_position_id'   => [ 'require', 'checkPosition' => '' ],
        'ads_sort'          => [ 'require', 'integer', 'gt' => 0, 'checkSort' => '' ],
        'ads_title'         => [ 'require' ],
        'ads_sub_title'     => [ 'require' ],
        'ads_sucai_id'      => [ 'require', 'checkSucai' => '' ],
        'ads_url'           => [ 'require', 'url', 'checkAdsUrl' => '' ],
        'ads_descript'      => [ 'require' ],
        'ads_days'          => [ 'require', 'checkAdsDays' => '' ],
    ];

    protected $field = [
        'ads_title'         => '广告标题',
        'ads_sub_title'     => '广告副标题',
        'ads_sucai_id'      => '广告素材',
        'ads_url'           => '投放链接',
        'ads_descript'      => '广告描述',
        'ads_position_id'   => '广告位',
        'ads_sort'          => '广告位置',
        'ads_days'          => '投放时间',
    ];

    protected $message = [
        'ads_url.checkAdsUrl'           => '投放链接只能使用本站链接',
        'ads_position_id.checkPosition' => '广告位不存在',
        'ads_sort.checkSort'            => '广告位置错误',
        'ads_sucai_id.checkSucai'       => '广告素材错误',
    ];


    public $scene = [
        'create' => [
            'ads_title',
            'ads_sub_title',
            'ads_descript',
            'ads_sucai_id',
            'ads_url',
            'ads_position_id',
            'ads_sort',
            'ads_days',
        ],
    ];

    function checkPosition($value,$rule)
    {
        $position = Fun::dataDetail(Fun::mApi('ads','AdsPosition'),$value);
        if ( empty($position) || $position['ads_position_state'] != State::STATE_NORMAL ) {
            return false;
        }
        $this->position = $position;
        return true;
    }

    function checkSort($value,$rule)
    {
        if ( $value > $this->position['ads_position_num'] ) {
            return false;
        }
        return true;
    }

    function checkSucai($value,$rule)
    {
        $map = [
            'ads_sucai_id'          => $value,
            'ads_sucai_user_id'     => intval(request()->user['user_id'] ?? 0),
            'ads_sucai_state'       => State::STATE_NORMAL,
            'ads_sucai_width'       => $this->position['ads_position_width'],
            'ads_sucai_height'      => $this->position['ads_position_height'],
        ];
        
        $ads_sucai = db('ads_sucai')->where($map)->find();
        if ( empty($ads_sucai) ) {
            return false;
        }
        $data = request()->data;
        $data['ads_sucai'] = $ads_sucai;
        request()->bind('data',$data);
        return true;
    }

    function checkAdsUrl($value,$rule)
    {
        $host = parse_url($value, PHP_URL_HOST);
        if ( false == $host ) {
            return false;
        }
        $host = explode(".", $host);
        $root[] = (string)array_pop($host);
        $root[] = (string)array_pop($host);
        $root = implode(".", array_reverse($root));
        if ( $root != config('url_domain_root') ) {
            dump($root);exit;
            return false;
        }
        return true;
    }

    function checkAdsDays($value,$rule)
    {
        $adsDays    = explode(',', $value);
        $param      = request()->data;
        $adsDaysInt = [];
        # 日期是否合法
        foreach ($adsDays as $day) {
            $adsDaysInt[] = $time = strtotime($day);
            if ($day != date('Y-m-d', $time) ) {
                return false;
            }
        }
        #是否已有被占用
        $days_use = Fun::mApi('ads','AdsPosition')->days_use($this->position['ads_position_id'], request()->data['ads_sort']);
        if ( false == Fun::mApi('ads','AdsPosition')->check_in_use($adsDays,$days_use) ) {
            return '存在已被投放的日期，请刷新页面重试';
        }
        # 限制日期范围
        $allowMin = date('Y-m-d',time() + 24 * 3600);
        $allowMax = date('Y-m', strtotime('+'. State::ADS_MAX_BUY_MONTH .' month')) . '-01';
        if ( min($adsDaysInt) < strtotime($allowMin) ) {
            return '投放日期过早';
        }
        if ( max($adsDaysInt) >= strtotime($allowMax) ) {
            return '投放日期只能选择最近' . State::ADS_MAX_BUY_MONTH . '个月';
        }
        # 重新绑定
        $param['ads_sday'] = date('Y-m-d',min($adsDaysInt));
        $param['ads_eday'] = date('Y-m-d',max($adsDaysInt));
        request()->bind('data',$param);
        return true;
    }

}