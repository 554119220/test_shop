<?php
namespace app\lzy\controller;
use app\common\traits\F as Fun;

class Generate extends Common
{



	public function create_model(){
		$tables = $this->getTable()[0];
		$tableInfo = [];
		if(in_array(input('table'), $tables)){
			$tableInfo = $this->getTableInfo();
		}
		// print_r($tableInfo);
		return view('model', [ 
			'tables' 			=> $tables, 
			'table' 			=> input('table'), 
			'tableInfo' 		=> $tableInfo, 
			'defaultNamespace' 	=> 'app\api\model',
			'Comments' 			=> $this->getTable()[1],
		]);
	}

	public function modelsave(){
		request()->isPost() or die('');
		$post = request()->post(false);
		$tableInfo = $this->getTableInfo();
		$tables = $this->getTable()[0];
		$Comments = $this->getTable()[1];
		// print_r($tableInfo);exit;
		$modelFile = [];
		$modelFile[] = '<?php';
		$modelFile[] = 'namespace ' . trim(trim($post['namespace']), ';') . ';';
		// $modelFile[] = PHP_EOL;
		$modelFile[] = '/**';
		$modelFile[] = ' * Create Model from Lzy ModelGenerate.';
		$modelFile[] = ' * @author Lzy';
		$modelFile[] = ' * @date ' . date('Y-m-d H:i:s');
		$modelFile[] = ' */';
		// $modelFile[] = PHP_EOL;
		$modelFile[] = 'class ' . $this->tableDeal($post['table']) . ' extends \think\Model';
		$modelFile[] = '{';
		$modelFile[] = '    protected $pk = \'' . $post['pk'] . "';";
		$modelFile[] = '    protected $append = [];';
		// $modelFile[] = '    protected $allowField = true;';
		$modelFile[] = '    protected $resultSetType = \'array\';';
		if($post['autoWriteTimestamp'] == 'true' || $post['autoWriteTimestamp'] == 'false'){
			$modelFile[] = '    protected $autoWriteTimestamp = ' . $post['autoWriteTimestamp'] . ';';
		}else{
			$modelFile[] = '    protected $autoWriteTimestamp = \'' . $post['autoWriteTimestamp'] . "';";
		}
		if ( $post['autoWriteTimestamp'] != 'false' ) {
			$modelFile[] = '    protected $createTime = \'' . $post['createTime'] . "';";
			$modelFile[] = '    protected $updateTime = \'' . $post['updateTime'] . "';";
			$modelFile[] = '    protected $dateFormat = \'' . $post['dateFormat'] . "';";
		}
		

		$auto = [];
		$insert = [];
		$update = [];
		$edit = [];
		foreach ($post['set'] as $key => $value) {
			switch($value['sure']){
				case 'edit':
					$edit[$key] = "'" . $key . "'";
					break;
				case 'auto':
					$auto[$key] = "'" . $key . "'";
					break;
				case 'insert':
					$insert[$key] = "'" . $key . "'";
					break;
				case 'update':
					$update[$key] = "'" . $key . "'";
					break;
				
			}
		}
		if($auto){
			$modelFile[] = '    protected $auto = ' . '[ ' . implode(', ', $auto) . ' ]' . ';';
		}else{
			$modelFile[] = '    protected $auto = [];';
		}
		if($insert){
			$modelFile[] = '    protected $insert = ' . '[ ' . implode(', ', $insert) . ' ]' . ';';
		}else{
			$modelFile[] = '    protected $insert = [];';
		}
		if($update){
			$modelFile[] = '    protected $update = ' . '[ ' . implode(', ', $update) . ' ]' . ';';
		}else{
			$modelFile[] = '    protected $update = [];';
		}
		// 修改器
		$modelFile[] = PHP_EOL;
		$modelFile[] = '    /**';
		$modelFile[] = '     ****************************************************************************************************';
		$modelFile[] = '     * 修改器 - insert&update 和 自动完成 **************************************************************';
		$modelFile[] = '     ****************************************************************************************************';
		$modelFile[] = '     */';
		$modelFile[] = PHP_EOL;

		foreach ($auto as $key => $value) 
		{
			$modelFile[] = '    /**';
			$modelFile[] = '     * ' . $key . ' - ' . $tableInfo['CommentInfo'][$key] ?? '未设置此字段注释';
			$modelFile[] = '     */';
			$modelFile[] = '    protected function set' . $this->tableDeal($key) .'Attr($value, $data)';
			$modelFile[] = '    {';
			$modelFile[] = "        " . implode(PHP_EOL . "        ", explode(PHP_EOL, $post['set'][$key]['value']));
			$modelFile[] = '    }';
			$modelFile[] = PHP_EOL;
		}
		
		foreach ($insert as $key => $value) 
		{
			$modelFile[] = '    /**';
			$modelFile[] = '     * ' . $key . ' - ' . $tableInfo['CommentInfo'][$key] ?? '未设置此字段注释';
			$modelFile[] = '     */';
			$modelFile[] = '    protected function set' . $this->tableDeal($key) .'Attr($value, $data)';
			$modelFile[] = '    {';
			$modelFile[] = "        " . implode(PHP_EOL . "        ", explode(PHP_EOL, $post['set'][$key]['value']));
			$modelFile[] = '    }';
			$modelFile[] = PHP_EOL;
		}
		
		foreach ($update as $key => $value) 
		{
			$modelFile[] = '    /**';
			$modelFile[] = '     * ' . $key . ' - ' . $tableInfo['CommentInfo'][$key] ?? '未设置此字段注释';
			$modelFile[] = '     */';
			$modelFile[] = '    protected function set' . $this->tableDeal($key) .'Attr($value, $data)';
			$modelFile[] = '    {';
			$modelFile[] = "        " . implode(PHP_EOL . "        ", explode(PHP_EOL, $post['set'][$key]['value']));
			$modelFile[] = '    }';
			$modelFile[] = PHP_EOL;
		}

		foreach ($edit as $key => $value) {
			$modelFile[] = '    /**';
			$modelFile[] = '     * ' . $key . ' - ' . $tableInfo['CommentInfo'][$key] ?? '未设置此字段注释';
			$modelFile[] = '     */';
			$modelFile[] = '    protected function set' . $this->tableDeal($key) .'Attr($value, $data)';
			$modelFile[] = '    {';
			$modelFile[] = "        " . implode(PHP_EOL . "        ", explode(PHP_EOL, $post['set'][$key]['value']));
			$modelFile[] = '    }';
			$modelFile[] = PHP_EOL;
		}
		
		// 获取器
		$modelFile[] = PHP_EOL;
		$modelFile[] = '    /**';
		$modelFile[] = '     ****************************************************************************************************';
		$modelFile[] = '     * 获取器 - select&find 自动处理 *******************************************************************';
		$modelFile[] = '     ****************************************************************************************************';
		$modelFile[] = '     */';
		$modelFile[] = PHP_EOL;

		foreach ($post['get'] as $key => $value) {
			if($value['sure']){
				$modelFile[] = '    /**';
				$modelFile[] = '     * ' . $key . ' - ' . $tableInfo['CommentInfo'][$key] ?? '未设置此字段注释';
				$modelFile[] = '     */';
				$modelFile[] = '    protected function get' . $this->tableDeal($key) .'Attr($value, $data)';
				$modelFile[] = '    {';
				$modelFile[] = "        " . implode(PHP_EOL . "        ", explode(PHP_EOL, $value['value']));
				$modelFile[] = '    }';
				$modelFile[] = PHP_EOL;
			}
		}
		// 自定义方法
		$modelFile[] = PHP_EOL;
		$modelFile[] = '    /**';
		$modelFile[] = '     ****************************************************************************************************';
		$modelFile[] = '     * 自定义方法 **************************************************************************************';
		$modelFile[] = '     ****************************************************************************************************';
		$modelFile[] = '     */';
		$modelFile[] = PHP_EOL;
		// 关联
		$modelFile[] = PHP_EOL;
		$modelFile[] = '    /**';
		$modelFile[] = '     ****************************************************************************************************';
		$modelFile[] = '     * 关联模型 ****************************************************************************************';
		$modelFile[] = '     ****************************************************************************************************';
		$modelFile[] = '     */';
		$modelFile[] = PHP_EOL;
		if(isset($post['relation']['hasOne'])){
			foreach ($post['relation']['hasOne'] as $key => $value) {
				$modelFile[] = '    /**';
				$modelFile[] = '     * 一对一关联 - ' . $value['table'] . ' - ' . $Comments[array_search($value['table'], $tables)];
				$modelFile[] = '     */';
				$modelFile[] = '    public function ' . $this->tableDeal($value['table']) . '()';
				$modelFile[] = '    {';
				$modelFile[] = "        " . 'return $this->hasOne("' . $this->tableDeal($value['table']) . '", "' . $value['foreignKey'] . '", "' . $value['localKey'] . '");';
				$modelFile[] = '    }';
				$modelFile[] = PHP_EOL;
			}
		}
		if(isset($post['relation']['hasMany'])){
			foreach ($post['relation']['hasMany'] as $key => $value) {
				$modelFile[] = '    /**';
				$modelFile[] = '     * 一对多关联 - ' . $value['table'] . ' - ' . $Comments[array_search($value['table'], $tables)];
				$modelFile[] = '     */';
				$modelFile[] = '    public function ' . $this->tableDeal($value['table']) . '()';
				$modelFile[] = '    {';
				$modelFile[] = "        " . 'return $this->hasMany("' . $this->tableDeal($value['table']) . '", "' . $value['foreignKey'] . '", "' . $value['localKey'] . '");';
				$modelFile[] = '    }';
				$modelFile[] = PHP_EOL;
			}
		}
		// 整合
		$modelFile[] = '}';
		$modelFile = implode(PHP_EOL, $modelFile);
		// print_r($post);exit;
		# 指定下载文件类型
		header('Content-Type: application/octet-stream');
		# 指定下载文件的描述
        header('Content-Disposition: attachment; filename="' . $this->tableDeal($post['table']) . '.php"');
		# 数据流
        header('Content-Transfer-Encoding: binary');
		# 指定下载文件的大小
        header('Content-Length:'.strlen($modelFile));
        echo $modelFile;
		// print_r($post);
	}

	public function tableDeal($table){
		if(strpos($table, '_')){
			$table = explode('_', $table);
			foreach ($table as $key => $value) {
				$table[$key] = ucfirst($value);
			}
			return implode("", $table);
		}else{
			return ucfirst($table);
		}
		
	}

	public function create_validate(){
		$tables = $this->getTable()[0];
		$tableInfo = [];
		if(in_array(input('table'), $tables)){
			$tableInfo = $this->getTableInfo();
		}
		$vList = ['require','number','integer','float','boolean','email','array','accepted','date','file','image','alpha','alphaNum','alphaDash','activeUrl','chs','chsAlpha','chsAlphaNum','chsDash','url','ip','dateFormat','in','notIn','between','notBetween','length','max','min','after','before','expire','allowIp','denyIp','confirm','different','egt','gt','elt','lt','eq','unique','regex','method','token','fileSize','fileExt','fileMime'];
		
		return view('validate',[
			'tables' 			=> $tables,
			'table' 			=> input('table'),
			'tableInfo' 		=> $tableInfo,
			'vList' 			=> $vList,
			'defaultNamespace' 	=> 'app\api\validate',
			'Comments' 			=> $this->getTable()[1],
		]);
	}

	public function validatesave(){
		request()->isPost() or die('');
		$post = request()->post(false);
		// PRINT_R($post);
		$vFile[] = '<?php';
		$vFile[] = 'namespace ' . trim(trim($post['namespace']), ';') . ';';
		// $vFile[] = PHP_EOL;
		$vFile[] = '/**';
		$vFile[] = ' * Create Model from Lzy ValidateGenerate.';
		$vFile[] = ' * @author Lzy';
		$vFile[] = ' * @date ' . date('Y-m-d H:i:s');
		$vFile[] = ' */';
		// $vFile[] = PHP_EOL;
		$vFile[] = 'class ' . $this->tableDeal($post['table']) . ' extends \think\Validate';
		$vFile[] = '{';
		// RULE
		$vFile[] = '    protected $rule = [';
		// $vFile[] = PHP_EOL;
		if(isset($post['validate'])){
			$field = [];
			foreach ($post['validate'] as $key => $value) {
				$field[$value['field']][] = [
					'rule' => $value['rule'],
					'value' => $value['value'],
					'message' => $value['message'],
				];
			}
			foreach ($field as $key => $value) {
				
				$line = '';
				foreach ($value as $k => $v) {
					if($v['value']){
						$line .= "'" . $v['rule'] . "' => '" . $v['value'] . "', ";
					}else{
						$line .= "'" . $v['rule'] . "', ";
					}
				}
				$vFile[] = '        ' . "'" . $key . "'" . ' => [ ' . rtrim($line, ', ') . ' ],';
			}
		}
		$vFile[] = '    ];';
		$vFile[] = PHP_EOL;

		// message
		$vFile[] = '    protected $message = [';
		// $vFile[] = PHP_EOL;
		if(isset($post['validate'])){
			$field = [];
			foreach ($post['validate'] as $key => $value) {
				$field[$value['field']][] = [
					'rule' => $value['rule'],
					'value' => $value['value'],
					'message' => $value['message'],
				];
			}
			foreach ($field as $key => $value) {
				$line = '';
				foreach ($value as $k => $v) {
					if($v['message']){
						$vFile[] = '        ' . "'$key." . $v['rule'] . "' => '" . $v['message'] . "',";
					}
				}
				$vFile[] = PHP_EOL;
			}
		}
		$vFile[] = '    ];';
		$vFile[] = PHP_EOL;

		//scene
		$vFile[] = '    public $scene = [';
		if(isset($post['scene'])){
			foreach($post['scene'] as $key => $value){
				if(! empty($value['value'])){
					$vFile[] = '        ' . "'" . $value['name'] . "' => [ '" . implode("', '", $value['value']) . "' ],";
				}else{
					$vFile[] = '        ' . "'" . $value['name'] . "' => [],";
				}
			}
		}
		$vFile[] = '    ];';

		$vFile[] = '}';

		// 整合
		$vFile = implode(PHP_EOL, $vFile);
		// print_r($post);exit;
		# 指定下载文件类型
		header('Content-Type: application/octet-stream');
		# 指定下载文件的描述
        header('Content-Disposition: attachment; filename="' . $this->tableDeal($post['table']) . '.php"');
		# 数据流
        header('Content-Transfer-Encoding: binary');
		# 指定下载文件的大小
        header('Content-Length:'.strlen($vFile));
        echo $vFile;
		// print_r($post);

	}

	public function getTable(){
		$tables = db()->query('show tables;');
		$tables = array_column($tables, 'Tables_in_' . config('database.database'));

		foreach ($tables as $key => $value) {
			$tables[$key] = substr($value, strlen(config('database.prefix')));
			$create = explode(PHP_EOL, db()->query('show create table ' . $value . ';')[0]['Create Table']);
			$end = explode(" ", $create[count($create) - 1]);
			$Comment = trim($end[count($end) - 1]);
			
			$Comments[$key] = strpos($Comment,'COMMENT') === false ? '没有此表的注释' : substr(trim($Comment, "'"), 9);
		}
		// print_r($Comments);
		return [$tables, $Comments];
	}

	public function getTableInfo(){
		$tableInfo = db(input('table'))->getTableInfo();
		$fields = db()->query('SHOW FULL COLUMNS FROM `' . config('database.prefix') . input('table') . '`');
		// print_r(db()->query('SHOW FULL COLUMNS FROM `' . config('database.prefix') . input('table') . '`'));exit;
		

		foreach ($fields as $key => $value) {
			$tableInfo['Comment'][] = $value['Comment'];
			$tableInfo['CommentInfo'][$tableInfo['fields'][$key]] = $value['Comment'];
		}
		
		return $tableInfo;
	}

	/**
	 * 检测api里面未定义的validate
	 * @return [type] [description]
	 */
	public function check_validate(){
		$dir = APP_PATH . 'api/controller/';
		$paths = Fun::dir_files($dir);
		foreach ($paths as $key => $value) {
			if ( false == is_array($value) ) {
				unset($paths[$key]);
			}
		}
		// $paths = array_keys($paths);
		// $paths = ;
		foreach ($paths as $key => $value) {
			$vpath = Fun::dir_files($dir . '/' . $key);
			foreach ($vpath as $k => $v) {
				if ( true == is_array($v) && preg_match('/^[0-9a-zA-Z]{1,}$/', $k) ) {
					$paths[$key][] = $k;
				}
			}
		}
		
		return view('',[
			'paths' => $paths,
		]);
	}

}