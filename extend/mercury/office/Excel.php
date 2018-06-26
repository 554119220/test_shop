<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/6 0006
 * Time: 11:48
 */

namespace mercury\office;

use traits\think\Instance;
include ROOT . 'vendor/phpoffice/phpexcel/Classes/PHPExcel.php';
/**
 * Class Excel
 * @package mercury\office
 *
 * PHPExcel
 */
class Excel
{
    use Instance;
    protected $handler, $file, $reader;

    public function __construct($file)
    {
        $this->file = $file;
        $this->reader   = new \PHPExcel_Reader_Excel2007();
    }

    public function read()
    {
        $PHPExcel       = $this->reader->load($this->file);
        $objWorksheet   = $PHPExcel->getActiveSheet();
        $highestRow     = $objWorksheet->getHighestRow(); // 取得总行数
        $highestColumn  = $objWorksheet->getHighestColumn(); // 取得总列数
        $arr    = array(1 => 'A', 2 => 'B', 3 => 'C', 4 => 'D', 5 => 'E', 6 => 'F', 7 => 'G', 8 => 'H', 9 => 'I', 10 => 'J', 11 => 'K', 12 => 'L', 13 => 'M', 14 => 'N', 15 => 'O', 16 => 'P', 17 => 'Q', 18 => 'R', 19 => 'S', 20 => 'T', 21 => 'U', 22 => 'V', 23 => 'W', 24 => 'X', 25 => 'Y', 26 => 'Z');
        //echo $highestRow.$highestColumn;
        // 一次读取一列
        $max    = current(array_keys($arr, $highestColumn));
        $res    = [];
        for ($row = 2; $row <= $highestRow; $row++) {
            for ($column = 1; $column <= $max; $column++) {
                $val = $objWorksheet->getCellByColumnAndRow($column, $row)->getValue();
                $res[$row-2][$column] = $val;
            }
        }
        if (empty($res)) return false;
        array_shift($res);  //移除第一个
        $data   = [];
        $datas  = [];
        foreach ($res as $k => $v) {
            if (!is_null($v[1])) {
                if (strlen($v[1]) == 1) {
                    $type   = $v[1];
                } else {
                    $type   = 1;
                }
            }
            $tmp['company_name']            = $v[2];
            $tmp['company_code']            = $v[3];
            $tmp['company_trajectory']      = is_null($v[4]) ? 0 : 1;
            $tmp['company_is_face_alone']   = is_null($v[5]) ? 0 : 1;
            $tmp['company_is_pickup']       = is_null($v[6]) ? 0 : 1;
            $tmp['company_group']           = $type;
            $data[$type][]  = $tmp;
            $datas[]  = $tmp;
        }
        //$flag   = db('express_company')->insertAll($datas);
    }
}