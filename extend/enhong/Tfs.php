<?php
/**
 * Tfs文件系统
 */
namespace enhong;
class Tfs
{
    /**
     * @param string $data 文件流
     * @param string $ext  扩展名，如.jpg|.png|.gif
     * @param array $config
     */
    static function upload($data,$ext,$config=[]){
        if(empty($config)) $config = config('cfg.tfs');
        $apiurl = $config['apiurl'] . '?simple_name=1&suffix='.$ext;
        $res = curl_post($apiurl,$data,1);
        $res = json_decode($res,true);

        if($res['TFS_FILE_NAME']) $result = ['code' => 1,'data' => ['url' => $config['domain'].$res['TFS_FILE_NAME'],'filesize' => strlen($data)]];
        else $result = ['code' => 0];

        return $result;
    }

    /**
     * 删除文件
     */

}

