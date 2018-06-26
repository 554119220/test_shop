<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/12 0012
 * Time: 11:01
 */

namespace mercury\office\export;
use app\common\traits\F;

/**
 * Class Export
 * @package mercury\office\export
 * @title excel导出
 */
abstract class Export
{
    protected
        #   模型对象
        $model,
        #   数据条件
        $map = [],
        #   列名
        $row_title = [],
        #   总列数
        $cnt_title,
        #   总行数
        $cnt_row = 0,
        #   excel文件名称
        $filename,
        #   保存路径
        $save_path,
        #   excel导出文件起始
        $file_number = 0,
        #   是否执行writeExcel
        $is_export = false,
        #   压缩后的文件名称
        $zip_filename,
        #   下载文件名称
        $download_filename,
        #   excel起始行
        $i = 2,
        #   唯一标识
        $uniquely_id;
    #   单个文件数据量
    const SINGLE_FILE_MAX_ROW   = 10000;
    #   每页数量
    const SQL_CHUNK             = 100;
    #   excel文件后缀
    const EXCEL_PREFIX          = 'xlsx';
    #   excel对象
    public static $oExcel       = [];

    #   列名
    protected $cell_name = [
        'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI', 'AJ', 'AK', 'AL', 'AM', 'AN', 'AO', 'AP', 'AQ', 'AR', 'AS', 'AT', 'AU', 'AV', 'AW', 'AX', 'AY', 'AZ'
    ];
    

    public function __construct()
    {
        $this->cnt_title    = count($this->row_title);
        //$this->uniquely_id  = md5(http_build_query(session('user')));
        //$this->filename     = sprintf('%s_%s',$this->uniquely_id, date('Y-m-d'));
        $this->fielname =date('Y-m-d');
        #  /www/download
        $this->save_path    = '/www/download/';
        $this->createDir();
//        dump(realpath(__DIR__));
//        dump(is_dir($this->save_path));
//        exit();
    }

    /**
     * @title run 执行方法
     * @return mixed
     */
    abstract protected function run();

    /**
     * @title yields 迭代方法
     * @param $key
     * @param $is_export
     * @return mixed
     */
    abstract protected function yields($key, $is_export, $orders);

    /**
     * @title down 下载文件
     * > you are api description
     */
    protected function down()
    {
        header('Content-Type: application/zip');
        header("Content-Disposition: attachment;filename={$this->filename}{$this->download_filename}.zip");
        header('Cache-Control: max-age=0');
        readfile($this->zip_filename);
        $this->removeZipFile();
        exit();
    }

    /**
     * @title setMap
     * @param array $map
     * @return $this
     */
    public function setMap(array $map)
    {
        $this->map  = $map;
        return $this;
    }

    /**
     * @title setDownloadFilename
     * @param $filename
     * @return $this
     */
    public function setDownloadFilename($filename)
    {
        $this->download_filename    = sprintf('%s_%s', $this->uniquely_id, $filename);
        return $this;
    }

    /**
     * @title setProperties 初始属性
     * @param $key
     * @return $this
     */
    protected function setProperties($key)
    {
        self::getExcelObject($key)->getProperties()
            ->setCreator('setCreator')
            ->setLastModifiedBy('setLastModifiedBy')
            ->setTitle('setTitle')
            ->setSubject('setSubject')
            ->setDescription('setDescription')
            ->setKeywords('setKeyWords')
            ->setCategory('setCategory');
        return $this;
    }

    /**
     * @title setSheetOne 第一行
     * @param $key
     * @return $this
     */
    protected function setSheetOne($key)
    {
        $d = 'A';
        foreach ($this->row_title as $k => $v) {
            self::getExcelObject($key)->setActiveSheetIndex(0)->setCellValue("{$d}1", $v);
            ++$d;
        }
        return $this;
    }

    /**
     * @title writeExcel 写入excel
     * @param $key
     */
    protected function writeExcel($key) {
        $this->i    = 2;
        $this->setProperties($key)->setSheetOne($key);
        $a = self::getExcelObject($key);
        $write  = \PHPExcel_IOFactory::createWriter($a, 'Excel2007');
        $filename   = $this->getFileName($key);
        $write->save("{$this->save_path}{$filename}.xlsx");
    }

    /**
     * @title getExcelObject
     * @return mixed
     */
    protected static function getExcelObject($key)
    {
        if (!isset(self::$oExcel[$key]) ||
            false == self::$oExcel[$key] instanceof \PHPExcel) {
            self::$oExcel[$key] = new \PHPExcel();
        }
        return self::$oExcel[$key];
    }
    
    /**
     * @title createZip 生成ZIP
     * @param array $files
     * @param string $zipFileName
     * @return string
     */
    protected function createZip($zipFileName = '')
    {
        $zipFileName    = $zipFileName ? : sprintf('%s%s.%s', $this->save_path, date('YmdHis'), 'zip');
        $zip    = new \ZipArchive();
        $zip->open($zipFileName, \ZipArchive::CREATE);
        $files  = [];
        for ($i = 0; $i <= $this->file_number; $i++) {
            $files[] = $path   = sprintf('%s%s.%s',$this->save_path, $this->getFileName($i),self::EXCEL_PREFIX);
            $zip->addFile($path, basename($path));
        }
        $zip->close();
        $this->removeFiles($files);
        return $zipFileName;
    }

    /**
     * @title removeFiles 移除文件
     * @param array $files
     */
    protected function removeFiles(array $files = [])
    {
        foreach ($files as $v) {
            if (is_file($v)) unlink($v);
        }
    }

    /**
     * @title removeZipFile
     */
    protected function removeZipFile()
    {
        if (is_file($this->zip_filename)) unlink($this->zip_filename);
    }

    /**
     * @title getZipFilename
     * @return string
     */
    protected function getZipFilename()
    {
        return "{$this->save_path}{$this->zip_filename}";
    }

    /**
     * @title getFileName 获取文件名
     * @param $key
     * @return string
     */
    protected function getFileName($key)
    {
        $map    = !empty($this->map) ? md5(http_build_query($this->map)) : '';
        return "{$map}{$this->filename}-{$key}";
    }

    protected function createDir()
    {
        if (!is_dir($this->save_path)) mkdir($this->save_path, 0777, true);
    }

    public function __destruct()
    {
        if (!empty(self::$oExcel)) {
            foreach (self::$oExcel as $k => $v) {
                unset(self::$oExcel[$k]);
            }
        }
    }
}