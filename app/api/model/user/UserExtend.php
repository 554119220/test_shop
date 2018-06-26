<?php
namespace app\api\model\user;
/**
 * Create Model from Lzy ModelGenerate.
 * @author Lzy
 * @date 2018-01-03 17:20:45
 */
class UserExtend extends \think\Model
{
    protected $pk = 'user_id';
    protected $append = [ 'user_start_years', 'user_end_years' ];
    protected $resultSetType = 'array';
    protected $autoWriteTimestamp = true;
    protected $createTime = 'user_extend_create_time';
    protected $updateTime = 'user_extend_update_time';
    protected $dateFormat = 'Y-m-d H:i:s';
    protected $auto = [];
    protected $insert = [];
    protected $update = [];




    /**
     ****************************************************************************************************
     * 修改器 - insert&update 和 自动完成 **************************************************************
     ****************************************************************************************************
     */


    /**
     * user_birthday - 生日
     */
    protected function setUserBirthdayAttr($value, $data)
    {
        return strtotime($value);
    }

    /**
     * user_birthday - 年代
     */
    protected function setUserBornAttr($value, $data)
    {
        $year = date('Y', strtotime($data['user_birthday']));
        
        return self::getYear($year, 'born');
    }

    /**
     * user_category - 年代
     */
    protected function setUserCategoryAttr($value, $data)
    {
        return implode(",", $value);
    }


    /**
     ****************************************************************************************************
     * 获取器 - select&find 自动处理 *******************************************************************
     ****************************************************************************************************
     */


    /**
     * user_birthday - 生日
     */
    protected function getUserBirthdayAttr($value, $data)
    {
        return date('Y-m-d', $value);
    }

    /**
     * user_category - 选择的偏好分类
     */
    protected function getUserCategoryAttr($value, $data)
    {
        return explode(",", $value);
    }


    /**
     ****************************************************************************************************
     * 自定义方法 **************************************************************************************
     ****************************************************************************************************
     */

    function getUserStartYearsAttr($value, $data)
    {
        $year = date('Y', $data['user_birthday']);
        return self::getYear($year, 'start');
    }

    function getUserEndYearsAttr($value, $data)
    {
        $year = date('Y', $data['user_birthday']);
        return self::getYear($year, 'end');
    }


    static function getYear($year,$get)
    {
        $res = null;
        $years = [
            [ 'born' => '50' , 'start' => 1950 , 'end' => 1959 ],
            [ 'born' => '60' , 'start' => 1960 , 'end' => 1969 ],
            [ 'born' => '70' , 'start' => 1970 , 'end' => 1979 ],
            [ 'born' => '80' , 'start' => 1980 , 'end' => 1984 ],
            [ 'born' => '85' , 'start' => 1985 , 'end' => 1989 ],
            [ 'born' => '90' , 'start' => 1990 , 'end' => 1994 ],
            [ 'born' => '95' , 'start' => 1995 , 'end' => 1999 ],
            [ 'born' => '00' , 'start' => 2000 , 'end' => date('Y') ],
        ];
        foreach ($years as $key => $value) {
            switch ($get) {
                case 'start':
                    if ( $year >= $value['start'] && $year <= $value['end'] ) {
                        $res = $value['start'];
                    }
                    break;
                case 'end':
                    if ( $year >= $value['start'] && $year <= $value['end'] ) {
                        $res = $value['end'];
                    }
                    break;
                case 'born':
                    if ( $year >= $value['start'] && $year <= $value['end'] ) {
                        $res = $value['born'];
                    }
                    break;
                default:
                    break;
            }
        }
        return $res;
    }

    /**
     ****************************************************************************************************
     * 关联模型 ****************************************************************************************
     ****************************************************************************************************
     */


}