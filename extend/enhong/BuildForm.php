<?php
/**
+----------------------------------------------------------------------
| RestFull API 
+----------------------------------------------------------------------
| 表单生成器
+----------------------------------------------------------------------
| Author: lazycat <673090083@qq.com>
+----------------------------------------------------------------------
*/

namespace enhong;
class BuildForm {
	protected $option;	//字段选项
	protected $item;	//单个表单项
	protected $methods = array('text','textarea','hidden','radio','checkbox','editor','date','select','password','file','html','images','verify','images_more','radio_list','checkbox_list','vcode','select_images','color','datetime','widget','button','code_editor','api_params','group','select_category','select_record','radio_box');
	protected $outhtml = array();	//表单生成后输出
	private $value = null;	//表单值
    /**
     * 架构函数
     * @access public
     * @param string $this->str  数据
     */
    public function __construct($option=[]) {
    }
	
	/**
	* 设置属性
	*/
	public function __set($name,$v){
		return $this->$name=$v;
	}

	/**
	* 获取属性
	*/
	public function __get($name){
		return isset($this->$name)?$this->$name:null;
	}
	
	/**
	* 销毁属性
	*/
    public function __unset($name) {
        unset($this->$name);
    }

    /**
    * 连贯操作的实现
    * @param string $method  方法
    * @param array 参数
    */
    public function __call($method,$args){
    	$method=strtolower($method);
    	if(in_array($method,$this->methods,true)) {
    		$action='_'.$method;
			$this->item = $this->$action($args[0]);
            $this->outhtml[] =   $this->_row($this->item);
            return $this;
        }else{
        	echo '调用类'.get_class($this).'中的方法'.$method.'()不存在';
        }
    }

    /**
    * 连贯操作后的结果组合
	* @param array $option 字段选项    
    */
    public function create(){
    	$html=@implode('',$this->outhtml);
    	$this->outhtml	= []; 	//销毁之前的内容以便create后重新生成
        $this->option	= [];
		$this->item 	= [];
    	return $html;
    }

	/**
	 * 输出单个表单项
	 */
	public function item(){
		$html = $this->_item_row($this->item);
		return $html;
	}
	/**
	* input
	* @param array $option 字段选项	
	*/
	public function _text($option=null){
		$this->option 	= $option;
		$value			= $this->_value($option);
		$attr 	= implode(' ',$this->_attr($option));
		$html 	='<input type="text" '.$attr.' value="'.htmlspecialchars($value).'">';

		//$html 	= $this->_row($html,$option);
		return $html;
	}

	/**
	 * password
	 * @param array $option 字段选项
	 */
	public function _password($option=null){
		$this->option 	= $option;
		$value	= $this->_value($option);
		$attr 	= implode(' ',$this->_attr($option));
		$html 	='<input type="password" '.$attr.' value="'.htmlspecialchars($value).'">';
		$html	.='<input type="hidden" name="_password_'.$option['name'].'" value="'.$value.'">';

		//$html 	= $this->_row($html,$option);
		return $html;
	}
	//创建文本框
	public function _textarea($option=null){
		$this->option 	= $option;
		$value	= $this->_value($option);
		$attr = implode(' ',$this->_attr($option));
		$html 	='<textarea '.$attr.'>'.$value.'</textarea>';

		//$html 	= $this->_row($html,$option);
		return $html;
	}

	//创建文本框
	public function _hidden($option=null){
		$this->option 	= $option;
		$value	= $this->_value($option);
		$attr 	= implode(' ',$this->_attr($option));
		$html 	='<input type="hidden" '.$attr.' value="'.$value.'">';
		return $html;
	}

	//创建按钮
	public function _button($option=null){
		$this->option = $option;
		$html	= [];
		foreach($option['btns'] as $val){
			$type = isset($val[2]) ? $val[2] : 'button';
			$attr = isset($val[3]) ? $val['3'] : '';
			$html[] = '<button type="'.$type.'" class="'.$val[1].'" '.$attr.'>'.$val[0].'</button>';
		}

		$html = implode(' ',$html);
		//$html 	= $this->_row($html,$option);
		return $html;
	}

	/**
	 * file
	 * @param array $option 字段选项
	 */
	public function _file($option=null){
		$this->option 	= $option;
		$value			= $this->_value($option);
		$attr 	= implode(' ',$this->_attr($option));
		$html 	='<input type="file" '.$attr.' value="">';

		//$html 	= $this->_row($html,$option);
		return $html;
	}

	/**
	 * date
	 * @param array $option 字段选项
	 */
	public function _date($option=null){
		$this->option 	= $option;
		$value			= $this->_value($option);
		$attr 	= implode(' ',$this->_attr($option));
		$attr   = str_replace('form-control','form-control date-picker',$attr);
		$html 	='<input type="text" '.$attr.' value="'.htmlspecialchars($value).'">';

		//$html 	= $this->_row($html,$option);
		return $html;
	}

	/**
	 * datetime
	 * @param array $option 字段选项
	 */
	public function _datetime($option=null){
		$this->option 	= $option;
		$value			= $this->_value($option);
		$attr 	= implode(' ',$this->_attr($option));
		$attr   = str_replace('form-control','form-control form_meridian_datetime',$attr);
		$html 	='<input type="text" '.$attr.' value="'.htmlspecialchars($value).'">';

		//$html 	= $this->_row($html,$option);
		return $html;
	}

	//创建图片上传
	public function _images($option=null){
		$this->option 	= $option;
		$value	= $this->_value($option);
		$attr 	= implode(' ',$this->_attr($option));
		$html 	= '<input type="hidden" '.$attr.' value="'.$value.'">';
		$img	= $value ? '<div class="sub-action"><i class="fa fa-times" onclick="deleteImage($(this))"></i></div>'.imgwh($value,100) : '<img src="'.thumb('/static/images/work/icon-images-add.png',100).'" alt="上传图片" onclick="uploadImages($(this))">';
		$html	.= '<ul class="form-images-list"><li>'.$img.'</li></ul>';

		return $html;
	}

	//创建图片上传
	public function _images_more($option=null){
		$this->option 	= $option;
		$value	= $this->_value($option);
		$attr 	= implode(' ',$this->_attr($option));
		$html 	= '<input type="hidden" '.$attr.' value="'.$value.'">';
		//$img	= $value ? '<div class="sub-action"><i class="fa fa-times" onclick="deleteImageMore($(this))"></i></div>'.imgwh($value,100) : '<img src="'.thumb('/images/work/icon-images-add.png',100).'" alt="上传图片" onclick="uploadImages($(this))">';
		$click 	= '<li class="click-upload"><img src="'.thumb('/static/images/work/icon-images-add.png',100).'" alt="上传图片" onclick="uploadImagesMore($(this))"></li>';
		$images_list = '';
		if($value) {
			if (!is_array($value)) $value = explode(',', $value);
			foreach ($value as $val) {
				$images_list .= '<li class="img-item" data-url="' . $val . '"><input type="hidden" name="' . $option['name'] . '[]" value="' . $val . '"><div class="sub-action"><i class="fa fa-times" onclick="deleteImageMore($(this))"></i></div>' . imgwh($val, 100) . '</li>';
			}
		}

		$html	.= '<ul class="form-images-list-more">'.$click.$images_list.'</ul>';

		return $html;
	}

	//select
	public function _select($option=null){
		$this->option = $option;
		$value	= (string)$this->_value($option);
		$attr = implode(' ',$this->_attr($option));
		$html 	='<select '.$attr.'" data-value="'.$value.'">';
		$is_first 	= isset($option['is_first']) && $option['is_first'] === 0 ? 0 : 1;
		if($is_first == 1) $html	.='<option value="">请选择'.(isset($option['label']) ? $option['label'] : '').'</option>';

		if(isset($option['data']) && is_array($option['data'])){
			if(isset($option['is_category']) && $option['is_category'] == 1){
				$html .= create_option($option['data'],$option['field'],$value);
			}elseif(isset($option['is_group']) && $option['is_group'] == 1){
                $html .= create_group_option($option['data'],$option['field'],$value);  //option group 只能是二级分类
            }else {
				foreach ($option['data'] as $val) {
					$selected = (string)$val[$option['field'][0]] === $value ? 'selected' : '';
					$html .= '<option value="' . $val[$option['field'][0]] . '" ' . $selected . '>' . $val[$option['field'][1]] . '</option>';
				}
			}
		}
		$html	.='</select>';

		//$html 	= $this->_row($html,$option);
		return $html;
	}

	//radio
	public function _radio($option=null){
		$this->option 	= $option;
		$value	= (string)$this->_value($option);
		$attr = implode(' ',$this->_attr($option));
		$html = '<div class="mt-radio-inline">';
		if(isset($option['data']) && is_array($option['data'])){
			foreach($option['data'] as $val){
				if(!is_array($val)) $val = [$val];
				$checked = (string)$val[$option['field'][0]] === $value ? 'checked="checked"' : '';
				$html .= '<label class="mt-radio mt-radio-outline">
                             <input type="radio" '.$attr.' value="'.$val[$option['field'][0]].'" '.$checked.'>'.$val[$option['field'][1]].'
                             <span></span>
                           </label>';
			}
		}
		$html .= '</div>';

		return $html;
	}

	//checkbox
	public function _checkbox($option=null){
		$this->option = $option;
		$value	= $this->_value($option);
		$value	= !is_array($value) ? @explode(',',$value) : $value;
		$attr = implode(' ',$this->_attr($option));
		$html = '<div class="mt-checkbox-inline">';
		if(isset($option['data']) && is_array($option['data'])){
			foreach($option['data'] as $val){
				if(!is_array($val)) $val = [$val];
				$checked = in_array($val[$option['field'][0]],$value) ? 'checked="checked"' : '';
				$html .= '<label class="mt-checkbox mt-checkbox-outline">
                             <input type="checkbox" '.$attr.' value="'.$val[$option['field'][0]].'" '.$checked.'>'.$val[$option['field'][1]].'
                             <span></span>
                           </label>';
			}
		}
		$html .= '</div>';

		return $html;
	}

	/**
	 * editor 百度编辑器 $('[data-type="ueditor"]') 去启用编辑器
	 * @param array $option 字段选项
	 */
	public function _editor($option=null){
		$option['style'] = $option['style'] ? $option['style'] : 'min-height:400px;';
		$this->option = $option;
		$value	= $this->_value($option);
		$attr 	= implode(' ',$this->_attr($option));
		$html	='<script data-type="ueditor" '.$attr.' type="text/plain">'.html_entity_decode($value).'</script>';
		return $html;
	}

	//代码编辑器
	public function _code_editor($option=null){
		$this->option 	= $option;
		$value	= $this->_value($option);
		$attr = implode(' ',$this->_attr($option));
		$attr .= 'data-type="code-editor"';
		if(!strpos($attr,'style=')) $attr .= 'style="min-height:200px;"';
		$html 	='<textarea '.$attr.'>'.$value.'</textarea>';

		//$html 	= $this->_row($html,$option);
		return $html;
	}

	//接口参数
	public function _api_params($option=null){
		$this->option 	= $option;
		$value	= $this->_value($option);
		$attr 	= implode(' ',$this->_attr($option));
		$html 	='<input type="hidden" '.$attr.' value="'.$value.'">';

		$html 	.= '<table class="table table-bordered table-hover valign-middle" data-type="api-params">';
		$html 	.= '<thead><th width="15%" class="text-center">名称</th><th width="15%" class="text-center">类型</th><th width="15%" class="text-center">是否必须</th><th width="15%" class="text-center">示例</th><th>描述</th></thead>';
		$html 	.= '<tbody>';

		if($value){
			$value = unserialize(html_entity_decode($value));
			foreach($value as $val){
				$html 	.= '<tr><td><input type="text" name="'.$option['name'].'_name[]" class="form-control" value="'.$val['name'].'"></td><td><input type="text" name="'.$option['name'].'_type[]" class="form-control" value="'.$val['type'].'"></td><td><select name="'.$option['name'].'_need[]" class="form-control"><option value="0" '.((int)$val['need'] == 0 ? 'selected':'').'>否</option><option value="1" '.((int)$val['need'] == 1 ? 'selected':'').'>是</option></select></td><td><input type="text" name="'.$option['name'].'_example[]" class="form-control" value="'.$val['example'].'"></td><td><input type="text" name="'.$option['name'].'_desc[]" class="form-control" value="'.$val['desc'].'"></td></tr>';
			}
		}

		$html 	.= '<tr><td><input type="text" name="'.$option['name'].'_name[]" class="form-control"></td><td><input type="text" name="'.$option['name'].'_type[]" class="form-control"></td><td><select name="'.$option['name'].'_need[]" class="form-control"><option value="0">否</option><option value="1">是</option></select></td><td><input type="text" name="'.$option['name'].'_example[]" class="form-control"></td><td><input type="text" name="'.$option['name'].'_desc[]" class="form-control"></td></tr>';
		$html 	.= '</tbody>';
		$html 	.= '</table><div class="btn-api-params-add btn btn-outline red"><i class="icon-plus"></i> 新增参数</div><div class="hidden example"></div>';

		return $html;
	}

    //group
    public function _group($option=null){
		//dump($this->value);
        $this->option 	= $option;
        $group  = is_array($option['options']) ? $option['options']: eval(html_entity_decode($option['options']));
        $html   = '<div class="row">';
        $col    = intval(12 / count($group));
        foreach($group as $key => $val){
            $html .= '<div class="col-xs-'.$col.' group-item">'.form_item($val,$this->value);
            $html .= '<div class="help-block"></div></div>';
        }
        $html .= '</div>';

        return $html;
    }

    //分类选择器，适合超多分类的情况
	public function _select_category($option=null){
		$this->option 	= $option;
		$value	= $this->_value($option);
		$attr 	= implode(' ',$this->_attr($option));
		$html 	='<input type="hidden" '.$attr.' value="'.$value.'">';

		$param  = isset($option['options']) ? eval(html_entity_decode($option['options'])) : [];
		$field  = isset($param['field']) ? $param['field'] : 'id,upid,category_name';
		$key    = isset($param['key']) ? $param['key'] : ['id','category_name'];
		$where  = isset($param['where']) ? $param['where'] : ['status' => 1,'upid' =>0 ];
		$max_depth  = isset($param['max_depth']) ? $param['max_depth'] : 3;
		$nav        = empty($value) ? '请选择……' : nav_sort2($value,$param);
		$order 	= isset($param['order']) ? $param['order'] : 'sort asc,id asc';

        $category = db($param['table'])->cache(true,30)->where($where)->field($field)->order($order)->select();

		$html   .= '<div class="select-category-box" data-name="'.$key[1].'" data-max_depth="'. $max_depth .'"><div class="hide options">'.serialize($param).'</div>';
		$html   .= '<div class="navsort"><div class="fl"><img src="/static/images/work/icon-select-record.png" alt="选择" onclick="$(this).closest(\'.select-category-box\').find(\'.scbox\').toggleClass(\'hide\')"></div><div class="select-record-box-item">' . $nav . '</div></div><div class="clearfix"></div>';
		$html   .= '    <div class="row scbox hide">';
		$html   .= '        <div class="col-xs-'.intval(12/$max_depth).' item" data-depth="1"><ul>';

		foreach($category as $val){
			$html .= '<li data-id="'.$val[$key[0]].'" onclick="form_select_category($(this))">'.$val[$key[1]].'</li>';
		}

		$html   .= '        </ul></div>';
		$html   .= '    </div>';
		$html   .= '</div>';

		return $html;
	}

	//记录选择器，适合记录非常多的情况下
	public function _select_record($option=null){
		$this->option 	= $option;
		$value	= $this->_value($option);
		//$value	= 7;
		$attr 	= implode(' ',$this->_attr($option));
		$html 	= '<input type="hidden" '.$attr.' value="'.$value.'">';
		$label	= isset($option['label']) ? $option['label'] : '';
		$record_value = $value ? select_record_value($value,eval(html_entity_decode($option['options']))) : '';

		$html	.= '<div class="select-record-box"><div class="fl"><img src="/images/work/icon-select-record.png" alt="选择" onclick="vmodal({title:\'选择'.$label.'\',url:\'/Selectrecord/index/field/'.$option['name'].'/fileds_id/'.$option['id'].'\',width:\'95%\'})"></div>';
		$html	.= '<div class="select-record-script">'.$record_value.'</div>';
		$html	.= '</div>';

		return $html;
	}
	
	//Radio box
	public function _radio_box($option=null){
		$this->option 	= $option;
		$value	= $this->_value($option);
		$attr 	= implode(' ',$this->_attr($option));
		$html 	='<input type="hidden" '.$attr.' value="'.$value.'">';

		$item_class	= isset($option['item_class']) ? $option['item_class'] : 'item-box';
		$item_class	= $item_class > 12 ? 12 : $item_class;

		$html	.= '<div class="radio-box"><ul>';
		foreach($option['data'] as $val){
			if(!is_array($val)) $val = [$val];
			$html .= '<li class="'.$item_class.($value == $val[$option['field'][0]] ? ' active' : '').'" data-value="'.$val[$option['field'][0]].'" onclick="radio_box($(this))">'.$val[$option['field'][1]].'</li>';
		}
		$html	.= '</ul></div>';

		return $html;
	}

    //html
    public function _html($option=null){
        $this->option 	= $option;
        //dump($this->option['html']);
        $html   = isset($this->option['html']) ? $this->option['html'] : '';

        return $html;
    }

	//表单属性
	public function _attr($option=null){
		$attr 	= [];
		$attr[] = (isset($option['name']) && $option['name']) ? 'name="'.$option['name'].($option['formtype'] == 'checkbox' ? '[]' : '').'" id="'.$option['name'].($option['formtype'] == 'checkbox' ? '[]' : '').'"' : '';
		$attr[] = (isset($option['style']) && $option['style']) ? 'style="'.$option['style'].'"' : '';
		$attr[] = (isset($option['attr']) && $option['attr']) ? $option['attr'] : '';

		$label = isset($option['label']) ? $option['label'] : '';
		if(!in_array($option['formtype'],['editor','hidden'])) {
			$attr[] = 'class="'.((isset($option['class']) && $option['class']) ? 'form-control ' . $option['class'] : 'form-control').'"';
			$attr[] = 'placeholder="' . ((isset($option['placeholder']) && $option['placeholder']) ? $option['placeholder'] : '请填写' . $label) . '"';
		}
		return $attr;
	}

	//表单值
	public function _value($option=null){
		if(isset($this->value[$option['name']]) && $this->value[$option['name']] !== '') return $this->value[$option['name']];
		elseif(isset($option['value']) && $option['value'] !== '') return $option['value'];
		elseif(isset($option['default']) && $option['default'] !== '') return $option['default'];

		return '';
	}

	//建立一行表单
	public function _row($html){
		$option = $this->option;
		if(isset($option['formtype']) && $option['formtype'] == 'hidden') return $html;

		$label 	= isset($option['label']) && $option['label'] ? $option['label'] : '&nbsp;';
		if(isset($option['is_need']) && $option['is_need'] == 1)$label .= '<span class="required"> * </span>';

		$name	= isset($option['name']) ? $option['name'] : '';
        $hide	= isset($option['hide']) ? $option['hide'] : '';
		//提示
		$tips = (isset($option['tips']) && $option['tips']) ? '<div class="tips">'.$option['tips'].'</div>' : '';

		$prev_addon 	= isset($option['prev_addon']) && $option['prev_addon'] ? '<span class="input-group-addon">'.$option['prev_addon'].'</span>' : '';
		$next_addon 	= isset($option['next_addon']) && $option['next_addon'] ? '<span class="input-group-addon">'.$option['next_addon'].'</span>' : '';

		$prev_btn 		= isset($option['prev_btn']) && $option['prev_btn'] ? '<span class="input-group-btn">'.$option['prev_btn'].'</span>' : '';
		$next_btn 		= isset($option['next_btn']) && $option['next_btn'] ? '<span class="input-group-btn">'.$option['next_btn'].'</span>' : '';

		$prev = $prev_btn ? $prev_btn : $prev_addon;
		$next = $next_btn ? $next_btn : $next_addon;

		if($prev || $next) $html = '<div class="input-group">'.$prev.$html.$next.'</div>';

		$html = '<div class="form-group'.($hide == 1 ? ' hide' : '').'" id="f-'.$name.'">
					<label class="control-label col-xs-3">'.$label.'</label>
					<div class="col-xs-6">'.$html. $tips .'</div>
				</div>';


		return $html;
	}

	//建立单个表单项
	public function _item_row($html){
		$option = $this->option;
		if($option['formtype'] == 'hidden') return $html;

		$prev_addon 	= isset($option['prev_addon']) && $option['prev_addon'] ? '<span class="input-group-addon">'.$option['prev_addon'].'</span>' : '';
		$next_addon 	= isset($option['next_addon']) && $option['next_addon'] ? '<span class="input-group-addon">'.$option['next_addon'].'</span>' : '';

		$prev_btn 		= isset($option['prev_btn']) && $option['prev_btn'] ? '<span class="input-group-btn">'.$option['prev_btn'].'</span>' : '';
		$next_btn 		= isset($option['next_btn']) && $option['next_btn'] ? '<span class="input-group-btn">'.$option['next_btn'].'</span>' : '';

		$prev = $prev_btn ? $prev_btn : $prev_addon;
		$next = $next_btn ? $next_btn : $next_addon;

		$input_group_attr = isset($option['input_group_attr']) ? ' '.$option['input_group_attr'] : '';
		if($prev || $next) $html = '<div class="input-group" '.$input_group_attr.'>'.$prev.$html.$next.'</div>';

		return  $html;
	}



	/**
     * 析构方法，清除
     */
	public function __destruct(){
	    unset($this->option);
        unset($this->outhtml);
		unset($this->item);
    }
}
