<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use yii\console\Controller;
use app\models\ActiveRecord\Mail;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class MailController extends Controller
{
    /**
     * This command send email
     * @param integer $status
     */
    public function actionSend($status = NULL)
    {
        exec("ps aux | grep 'mail/send'", $out);

        $matches = 0;

        foreach ($out AS $one_str) {
            if (strpos($one_str, "php") !== false && strpos($one_str, "yii") !== false) {
                $matches++;

                if ($matches >= 2) {
                    echo "\n\nWaiting...\n";
                    return 0;
                }
            }
        }

        if ($status === NULL) {
            $status = Mail::STATUS_INACTIVE;
        }

        $mails = Mail::find()->where(['status' => $status])->orderBy(['create_time' => SORT_ASC])->all();

        foreach ($mails AS $mail) {
            if (!$mail->mail_setting_id) {
                $mail->status = Mail::STATUS_ERROR;
                $mail->send_errors = $mail->send_errors.date('[Y-m-d H:i:s]')." - Settings not found\n";
                $mail->save();
                continue;
            }

            $mail->status = Mail::STATUS_ACTIVE;
            $mail->save();

            $mailer = new PHPMailer(true);

            try {
                $mailer->SMTPDebug = 2;
                $mailer->isSMTP();
                $mailer->Host = $mail->mailSetting->smtp_host;
                $mailer->SMTPAuth = true;
                $mailer->Username = $mail->mailSetting->smtp_user;
                $mailer->Password = $mail->mailSetting->smtp_password;

                if ($mail->mailSetting->smtp_secure) {
                    $mailer->SMTPSecure = strtolower($mail->mailSetting->smtp_secure);
                } else {
                    $mailer->SMTPAutoTLS = 0;
                }

                $mailer->Port = $mail->mailSetting->smtp_port;

                if ($mail->mailSetting->from_name) {
                    $mailer->setFrom($mail->mailSetting->from_email, $mail->mailSetting->from_name);
                } else {
                    $mailer->setFrom($mail->mailSetting->from_email);
                }

                $mailer->addAddress($mail->to_email);
                $mailer->CharSet = "utf-8";

                if ($mail->body_html) {
                    $mailer->isHTML(true);
                    $mailer->Body = $mail->body_html;

                    if ($mail->body_text) {
                        $mailer->AltBody = $mail->body_text;
                    }
                } else {
                    $mailer->Body = $mail->body_text;
                }

                $mailer->Subject = $mail->subject;

                $mailer->send();

                $mail->status = Mail::STATUS_SENDED;
                $mail->send_time = date('Y-m-d H:i:s');
                $mail->save();
            } catch (Exception $e) {
                $mail->status = Mail::STATUS_ERROR;
                $mail->send_errors = $mail->send_errors.date('[Y-m-d H:i:s]')." - ".$mailer->ErrorInfo."\n";
                $mail->save();
            }
        }
    }
}