<?php
namespace app\api\validate\goods\v1;
/**
 * Create Model from Lzy ValidateGenerate.
 * @author Lzy
 * @date 2017-11-16 15:41:17
 */
class Comment extends \think\Validate
{
    protected $rule = [
        'goods_comment_id' => [ 'require' ],
        'goods_comment_reply_content' => [ 'require', 'max' => 255 ],
    ];

    protected $field = [
        'goods_comment_id'              => '评价id',
        'goods_comment_reply_content'   => '回复内容',
    ];

    public $scene = [
        'index' => [],
        'reply' => [ 'require', 'goods_comment_reply_content' ],
    ];
}