<?php
/**
 * 发送邮件
 */
namespace enhong;
vendor('PHPMailer.class#phpmailer');
class Sendmail
{
    static public function send($param){
        $host       = config('cfg.smtp')['host'];
        $port       = config('cfg.smtp')['port'];
        $username   = config('cfg.smtp')['username'];
        $password   = config('cfg.smtp')['password'];

        $readto         = isset($param['readto']) ? $param['readto'] : config('cfg.smtp')['email'];
        $from           = isset($param['from']) ? $param['from'] : $username;
        $from_name      = isset($param['from_name']) ? $param['from_name'] : '百望富通';
        $to             = $param['to'];
        $to_name        = isset($param['to_name']) ? $param['to_name'] : '收件人';
        $subject        = isset($param['subject']) ? $param['subject'] : '来自百望富通的邮件';
        $body           = isset($param['body']) ? $param['body'] : '太懒了，没有邮件内容！';
        $att            = isset($param['att']) ? $param['att'] : '';
        $reto           = isset($param['reto']) ? $param['reto'] : '';
        $reto_name      = isset($param['reto_name']) ? $param['reto_name'] : '';
        $cc             = isset($param['cc']) ? $param['cc'] : '';
        $bcc            = isset($param['bcc']) ? $param['bcc'] : '';
        $charset        = isset($param['charset']) && $param['charset'] ? $param['charset'] : 'utf-8';

        $mail = new \PHPMailer\PHPMailer();
        $mail->CharSet	  = $charset;
        $mail->IsSMTP();							// tell the class to use SMTP
        $mail->SMTPAuth   = true;					// enable SMTP authentication
        //$mail->SMTPKeepAlive = true;              // SMTP connection will not close after each email sent
        $mail->Port       = $port;                  // set the SMTP server port
        $mail->Host       = $host;					// SMTP server
        $mail->Username   = $username;				// SMTP server username
        $mail->Password   = $password;				// SMTP server password

        if($readto) $mail->ConfirmReadingTo=$readto;    //读后通知邮箱
        if($reto) $mail->AddReplyTo($reto,$reto_name);  //回复
        if($cc) $mail->AddCC($cc);  //密送
        if($bcc) $mail->AddBCC($bcc); //抄送

        $mail->From=$from;
        $mail->FromName=$from_name;
        $mail->AddAddress($to,$to_name);
        $mail->Subject      = $subject;
        $mail->AltBody      = "这是一封HTML邮件，请用HTML方式浏览!";
        $mail->WordWrap     = 80;
        $mail->MsgHTML($body);
        $mail->IsHTML(true);

        if($att) {
            if(!is_array($att)) $att[0]=$att;
            foreach($att as $val){
                $mail->AddAttachment($val); //附件
            }
        }

        //dump($mail);exit();

        if(!$mail->Send()){
            return ['code' => 0,'msg' => '邮件发送失败！'];
        }else{
            return ['code' => 1,'msg' => '邮件已发送！'];
        }
    }

}

