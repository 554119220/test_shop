<?php
namespace app\api\validate;
use think\Validate;
class AccountLog extends Validate
{
    protected $rule = [
        'employee_id'       => 'require|integer|gt:0',       //不用必填，会有系统自动操作的情况
        'userid'            => 'require|integer|gt:0',
        'type'              => 'require|integer',
        'reason'            => 'require',
    ];

    protected $message = [
        'employee_id.require'   =>  '雇员ID必填',
        'userid.require'        =>  '用户必填',
        'type.require'          =>  '账户类型必填',
        'reason.require'        =>  '原因或备注必填',
    ];

    protected $scene = [
        'add'       => ['employee_id','userid','type','reason'],
    ];

}
