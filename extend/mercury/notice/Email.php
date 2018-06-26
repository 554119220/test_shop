<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/27 0027
 * Time: 16:32
 */

namespace mercury\notice;
use app\common\traits\F;
use mercury\constants\Cache;
use think\Exception;

/**
 * Class Email
 * @package app\common\notice
 *
 * swift Mail http://www.cnblogs.com/yuanke/p/5287334.html
 * require_once ("lib/swift_required.php");

// 创建Transport对象，设置邮件服务器和端口号，并设置用户名和密码以供验证
$transport = Swift_SmtpTransport::newInstance('smtp.163.com', 25)
->setUsername('username@163.com')
->setPassword('password');

// 创建mailer对象
$mailer = Swift_Mailer::newInstance($transport);

// 创建message对象
$message = Swift_Message::newInstance();

// 设置邮件主题
$message->setSubject('这是一份测试邮件')

// 设置邮件内容,可以省略content-type
->setBody(
'<html>' .
' <head></head>' .
' <body>' .
' Here is an image <img src="' . // 内嵌文件
$message->embed(Swift_Image::fromPath('image.jpg')) .
'" alt="Image" />' .
' Rest of message' .
'<a href="http://www.baidu.com">百度</a>'.
' </body>' .
'</html>',
'text/html'
);

// 创建attachment对象，content-type这个参数可以省略
$attachment = Swift_Attachment::fromPath('image.jpg', 'image/jpeg')
->setFilename('cool.jpg');

// 添加附件
$message->attach($attachment);

// 用关联数组设置收件人地址，可以设置多个收件人
$message->setTo(array('to@qq.com' => 'toName'));

// 用关联数组设置发件人地址，可以设置多个发件人
$message->setFrom(array(
'from@163.com' => 'fromName',
));

// 添加抄送人
$message->setCc(array(
'Cc@qq.com' => 'Cc'
));

// 添加密送人
$message->setBcc(array(
'Bcc@qq.com' => 'Bcc'
));

// 设置邮件回执
$message->setReadReceiptTo('receipt@163.com');

// 发送邮件
$result = $mailer->send($message);
 */
class Email extends Notice
{
    protected $message, $mailer, $transport;
    public function __construct(array $params, array $config = [])
    {
        parent::__construct('email', $params, $config);
        try {
            if (!isset($this->params['subject']) || empty($this->params['subject'])) throw new Exception('邮件主题不能为空');
            if (!isset($this->params['content']) || empty($this->params['content'])) throw new Exception('邮件内容不能为空');
        } catch (Exception $e) {
            exit($e->getMessage());
        }
        $this->mailer   = \Swift_Mailer::newInstance($this->getTransport());
        $this->message  = \Swift_Message::newInstance();
    }

    /**
     * 发送邮件
     *
     * @return int
     */
    public function send()
    {
        if (false === $this->max()) return false;
        #   邮件发送
        $this->message->setSubject($this->params['subject'])
            ->setBody($this->getContent())
            ->setTo(
                $this->getSetTo()
            )
            ->setFrom([
                $this->config['user'] => $this->getFromName()
            ]);
        return $this->mailer->send($this->message);
    }

    /**
     * 获取收件人信息
     *
     * @return array
     */
    public function getSetTo()
    {
        $tos    = explode(',', $this->getTo());
        $tmp    = [];
        foreach ($tos as $k => $v) {
            $tmp[$v]    = substr($v, 0, strpos($v, '@'));
        }
        unset($tos);
        return $tmp;
    }
    
    /**
     * 获取 transport
     */
    protected function getTransport()
    {
        if (!$this->transport) {
                $this->transport    = \Swift_SmtpTransport::newInstance($this->config['host'], $this->config['port'])
                    ->setUsername($this->config['user'])->setPassword($this->config['pass']);
        }
        return $this->transport;
    }
    
    /**
     * 获取发送人
     *
     * @return bool|string
     */
    protected function getFromName()
    {
        if (isset($this->params['from'])) return $this->params['from'];
        return substr($this->config['user'], 0, strpos($this->config['user'], '@'));
    }

    /**
     * 获取接收人
     *
     * @return bool|string
     */
    protected function getToName()
    {
        if (isset($this->params['to'])) return $this->params['to'];
        return substr($this->getTo(), 0, strpos($this->getTo(), '@'));
    }
}