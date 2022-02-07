<?php

/**
 * 安全检查
 * @author LiuMinChao
 */
class PhpmailController extends AuthBaseController {

    public $defaultAction = 'list';
    public $gridId = 'example2';
    public $contentHeader = '';
    public $bigMenu = '';
    const STATUS_NORMAL = 0; //正常

    public function init() {
        parent::init();
        $this->contentHeader = Yii::t('comp_safety', 'contentHeader');
        $this->bigMenu = Yii::t('comp_safety', 'bigMenu');
    }
    public function actionMail() {
        header("content-type:text/html;charset=utf-8");
        ini_set("magic_quotes_runtime",0);
//        require 'class.phpmailer.php';
        $tcpdfPath = Yii::app()->basePath.DIRECTORY_SEPARATOR.'extensions'.DIRECTORY_SEPARATOR.'PHPMailer'.DIRECTORY_SEPARATOR.'class.phpmailer.php';
        require_once($tcpdfPath);
        try {
            $mail = new PHPMailer(true);
            $mail->IsSMTP();
            $mail->CharSet='UTF-8'; //设置邮件的字符编码，这很重要，不然中文乱码
            $mail->SMTPAuth   = true;                  //开启认证
            $mail->Port       = 465;
            $mail->Host       = "smtp.exmail.qq.com";
            $mail->Username   = "service@cmstech.sg";
            $mail->SMTPSecure = 'ssl';
            $mail->Password   = "Wj1109";
            //$mail->IsSendmail(); //如果没有sendmail组件就注释掉，否则出现“Could  not execute: /var/qmail/bin/sendmail ”的错误提示
            //$mail->AddReplyTo("648700755@qq.com","liumc");//回复地址
            $mail->From       = "service@cmstech.sg";
            $mail->FromName   = "CMS";
            $mail->AddAddress("18364195378@163.com","liumc");
            $mail->Subject  = "phpmailer测试标题";
            $mail->Body = "<h1>phpmail演示</h1>这是php点点通（<font color=red>www.phpddt.com</font>）对phpmailer的测试内容";
            $mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; //当邮件不支持html时备用显示，可以省略
            $mail->WordWrap   = 80; // 设置每行字符串的长度
            $mail->AddAttachment("./template/excel/Staff.xls");  //可以添加附件
            $mail->IsHTML(true);
            $mail->Send();
            echo '邮件已发送';
        } catch (phpmailerException $e) {
            echo "邮件发送失败：".$e->errorMessage();
        }
    }
}
