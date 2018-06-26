<?php
namespace app\api\model\goods;
use app\common\traits\F;

/**
 * Create Model from Lzy ModelGenerate.
 * @author Lzy
 * @date 2017-11-15 14:14:49
 */
class GoodsCommentContent extends \think\Model
{
    protected $pk = 'goods_comment_id';
    protected $append = [];
    protected $resultSetType = 'array';
    protected $autoWriteTimestamp = false;
    protected $createTime = 'goods_comment_content_create_time';
    protected $updateTime = 'goods_comment_content_update_time';
    protected $dateFormat = 'Y-m-d H:i:s';
    protected $auto = [];
    protected $insert = [ 'goods_comment_images', 'goods_comment_reply_content', 'goods_comment_reply_time' ];
    protected $update = [];


    /**
     ****************************************************************************************************
     * 修改器 - insert&update 自动完成 *****************************************************************
     ****************************************************************************************************
     */


    /**
     * goods_comment_images - 评价图片
     */
    protected function setGoodsCommentImagesAttr($value, $data)
    {
        return (string) $value;
    }


    /**
     * goods_comment_reply_content - 商家回复内容
     */
    protected function setGoodsCommentReplyContentAttr($value, $data)
    {
        return empty($value) ? '' : (string)$value;
    }


    /**
     * goods_comment_reply_time - 回复时间
     */
    protected function setGoodsCommentReplyTimeAttr($value, $data)
    {
        return intval($value);
    }




    /**
     ****************************************************************************************************
     * 获取器 - select&find 自动完成 *******************************************************************
     ****************************************************************************************************
     */

    /**
     * 获取图片
     *
     * @param $value
     * @return array|string
     */
    public function getGoodsCommentImagesAttr($value)
    {
        if (empty($value)) return '';
        $images = explode(',', trim($value, ','));
        if (empty($value)) return '';
        $tmp    = [];
        foreach ($images as $v) {
            $tmp[]  = F::getImages($v);
        }
        return $tmp;
    }
    

    /**
     * goods_comment_reply_time - 回复时间
     */
    protected function getGoodsCommentReplyTimeAttr($value, $data)
    {
        return date('Y-m-d H:i:s', $value);
    }

    /**
     * goods_comment_reply_time - 回复时间
     */
    protected function getGoodsCommentContentAttr($value, $data)
    {
        return $value;
        return str_replace("\n", '<br>', $value);
    }

    /**
     * goods_comment_reply_time - 回复时间
     */
    protected function getGoodsCommentReplyContentAttr($value, $data)
    {
        return $value;
        return str_replace("\n", '<br>', $value);
    }

    /**
     ****************************************************************************************************
     * 自定义方法 **************************************************************************************
     ****************************************************************************************************
     */




    /**
     ****************************************************************************************************
     * 关联模型 ****************************************************************************************
     ****************************************************************************************************
     */


}