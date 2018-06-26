<?php
namespace app\api\validate;
use think\Validate;
class Application extends Validate
{
    protected $rule = [
        'status'        => 'require|integer|in:1,2,3',
        'userid'        => 'require|integer|gt:0',
        'reason'        => 'require|max:200',
        'slimit'        => 'integer|integer|gt:0',
        'dlimit'        => 'integer|integer|gt:0',
        'mlimit'        => 'integer|integer|gt:0',
        'type'          => 'require|in:1,2',
        'picture'       => 'require',
    ];

    protected $message = [
        'status.require'        =>  '状态必填',
        'userid.require'        =>  '用户必填',
        'type.require'          =>  '调额类型必填',
        'reason.require'        =>  '原因或备注必填',
        'picture.require'       =>  '证明图片必填',
    ];

    protected $scene = [
        'day_add'       => ['status','userid','type','reason','slimit','dlimit','picture'],
        'month_add'     => ['status','userid','type','reason','mlimit','picture'],
    ];

}
