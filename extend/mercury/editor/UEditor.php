<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/8 0008
 * Time: 16:44
 */

namespace mercury\editor;

use think\View;

/**
 * Class Ueditor
 * @package mercury\editor
 *
 * 编辑器
 */
class UEditor
{

    //例子
    /**
     * UEditor::getInstance(request()->module())
     *  ->setToolbar(['anchor', 'undo', 'redo'])
     *  ->setId('content')
     *  ->setTemplate('path/xxx/xxx.html')
     *  ->create();
     */

    /**
     * @var $instance 实例
     * @var $js_path 是否需要加载脚本，0不需要，1需要
     */
    protected static $instance, $js_path = 1;

    /**
     * @var array $toolbar 工具栏
     */
    public $toolbars  = [
        'anchor', //锚点
        'undo', //撤销
        'redo', //重做
        'bold', //加粗
        'indent', //首行缩进
        'snapscreen', //截图
        'italic', //斜体
        'underline', //下划线
        'strikethrough', //删除线
        'subscript', //下标
        'fontborder', //字符边框
        'superscript', //上标
        'formatmatch', //格式刷
        'source', //源代码
        'blockquote', //引用
        'pasteplain', //纯文本粘贴模式
        'selectall', //全选
        'print', //打印
        'preview', //预览
        'horizontal', //分隔线
        'removeformat', //清除格式
        'time', //时间
        'date', //日期
        'unlink', //取消链接
        'insertrow', //前插入行
        'insertcol', //前插入列
        'mergeright', //右合并单元格
        'mergedown', //下合并单元格
        'deleterow', //删除行
        'deletecol', //删除列
        'splittorows', //拆分成行
        'splittocols', //拆分成列
        'splittocells', //完全拆分单元格
        'deletecaption', //删除表格标题
        'inserttitle', //插入标题
        'mergecells', //合并多个单元格
        'deletetable', //删除表格
        'cleardoc', //清空文档
        'insertparagraphbeforetable', //"表格前插入行"
        'insertcode', //代码语言
        'fontfamily', //字体
        'fontsize', //字号
        'paragraph', //段落格式
        'simpleupload', //单图上传
        'insertimage', //多图上传
        'edittable', //表格属性
        'edittd', //单元格属性
        'link', //超链接
        'emotion', //表情
        'spechars', //特殊字符
        'searchreplace', //查询替换
        'map', //Baidu地图
        'gmap', //Google地图
        'insertvideo', //视频
        'help', //帮助
        'justifyleft', //居左对齐
        'justifyright', //居右对齐
        'justifycenter', //居中对齐
        'justifyjustify', //两端对齐
        'forecolor', //字体颜色
        'backcolor', //背景色
        'insertorderedlist', //有序列表
        'insertunorderedlist', //无序列表
        'fullscreen', //全屏
        'directionalityltr', //从左向右输入
        'directionalityrtl', //从右向左输入
        'rowspacingtop', //段前距
        'rowspacingbottom', //段后距
        'pagebreak', //分页
        'insertframe', //插入Iframe
        'imagenone', //默认
        'imageleft', //左浮动
        'imageright', //右浮动
        'attachment', //附件
        'imagecenter', //居中
        'wordimage', //图片转存
        'lineheight', //行间距
        'edittip ', //编辑提示
        'customstyle', //自定义标题
        'autotypeset', //自动排版
        'webapp', //百度应用
        'touppercase', //字母大写
        'tolowercase', //字母小写
        'background', //背景
        'template', //模板
        'scrawl', //涂鸦
        'music', //音乐
        'inserttable', //插入表格
        'drafts', // 从草稿箱加载
        'charts', // 图表
    ];

    /**
     * @var $filer 要过滤的路径
     * @var $template 模板路径
     * @var $width 编辑器宽度
     * @var $height 编辑器高度
     */
    protected $filer,
        $template = 'public/ueditor/index.html',
        $width = 1024,
        $height = 500,
        $id = 'content',
        $baseContent = '';

    public function __construct($filter = '')
    {
        $this->filer = $filter;
    }
    
    /**
     * 取得实例
     *
     * @return static
     */
    public static function getInstance($filter = '')
    {
        if (false == self::$instance instanceof self) {
            self::$instance = new static($filter);
        } else {
            self::$js_path  = 0;
        }
        return self::$instance;
    }


    /**
     * 删除一些不可用的元素
     *
     * @param array $remove
     * @return $this
     */
    public function removeToolbar(array $remove = [])
    {
        //dump($this->toolbars);
        if (!empty($remove)) {
            $tmp    = [];
            array_filter($this->toolbars, function(&$val) use ($remove, &$tmp) {
                if (!in_array($val, $remove)) {
                    $tmp[] = $val;
                }
            });
        }
        $this->toolbars = $tmp;
        unset($tmp);
        return $this;
    }

    /**
     * 设置编辑器宽高
     *
     * @param $with
     * @param $height
     * @return $this
     */
    public function setWidthHeight($with, $height)
    {
        $this->height   = $height;
        $this->width    = $with;
        return $this;
    }
    
    /**
     * 设置bar
     *
     * @param array $bars
     * @return $this
     */
    public function setToolbar(array $bars = [])
    {
        if (!empty($bars)) $this->toolbars = $bars;
        return $this;
    }

    /**
     * 设置编辑器ID
     *
     * @param $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id   = $id;
        return $this;
    }

    /**
     * 设置模板
     *
     * @param $templatePath
     * @return $this
     */
    public function setTemplate($templatePath)
    {
        $this->template = $templatePath;
        return $this;
    }

    /**
     * 设置初始化内容
     *
     * @param $baseContent
     * @return $this
     */
    public function setBaseContent($baseContent)
    {
        $this->baseContent = $baseContent;
        return $this;
    }

    /**
     * 创建编辑器
     *
     * @return string
     */
    public function create()
    {
        //需要剔除当前模块路径
        $template   = config('template.view_path') . $this->template;
        if (!empty($this->filer)) {
            $template = str_replace($this->filer, '', $template);
        }
        $data   = [
            'width'     => $this->width,
            'height'    => $this->height,
            'bars'      => $this->parseToolBas(),
            'id'        => $this->id,
            'js_path'   => self::$js_path,
            'baseContent' => $this->baseContent,
        ];
        // dump($data);exit;
        $view   = new View();
        $view->data = $data;
        return $view->fetch($template);
    }

    /**
     * 解析成['anchor','undo','redo']
     *
     * @return string
     */
    protected function parseToolBas()
    {
        $str    = '[';
        array_filter($this->toolbars, function ($val) use (&$str) {
            $str    .= "'{$val}',";
        });
        $str    = rtrim($str, ',');
        $str    = "{$str}]";
        return $str;
    }
}