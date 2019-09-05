<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use yii\console\Controller;
use yii\db\Query;
use app\models\ActiveRecord\Mail;
use app\models\ActiveRecord\MailSetting;
use app\models\ActiveRecord\Subscribe;
use app\models\ActiveRecord\Subscriber;
use app\models\ActiveRecord\SendedSubscribe;
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

    public function actionSendSubscribe()
    {
        $subscribes = Subscribe::find()->where(['status' => Subscribe::STATUS_ACTIVE])->orderBy(['id' => SORT_ASC])->all();
        $mail_setting = MailSetting::findOne(['status' => MailSetting::STATUS_ACTIVE]);

        if ($subscribes && !$mail_setting) {
            echo "No mail setting found";
            exit('1');
        }

        foreach ($subscribes AS $subscribe) {
            $subscribe->status = Subscribe::STATUS_SENDING;
            $subscribe->save();

            $query = new Query;
            $subscribers = $query->select(['id', 'email', 'hash'])
                                 ->from(Subscriber::tableName())
                                 ->where(['status' => Subscriber::STATUS_ACTIVE])
                                 ->andWhere('id NOT IN (SELECT subscriber_id FROM '.SendedSubscribe::tableName().')')
                                 ->all();

            foreach ($subscribers AS $subscriber) {

                $mailer = new PHPMailer(true);

                try {
                    $mailer->SMTPDebug = 2;
                    $mailer->isSMTP();
                    $mailer->Host = $mail_setting->smtp_host;
                    $mailer->SMTPAuth = true;
                    $mailer->Username = $mail_setting->smtp_user;
                    $mailer->Password = $mail_setting->smtp_password;

                    if ($mail_setting->smtp_secure) {
                        $mailer->SMTPSecure = strtolower($mail_setting->smtp_secure);
                    } else {
                        $mailer->SMTPAutoTLS = 0;
                    }

                    $mailer->Port = $mail_setting->smtp_port;

                    if ($mail_setting->from_name) {
                        $mailer->setFrom($mail_setting->from_email, $mail_setting->from_name);
                    } else {
                        $mailer->setFrom($mail_setting->from_email);
                    }

                    $mailer->addAddress($subscriber['email']);
                    $mailer->CharSet = "utf-8";
                    $mailer->isHTML(true);
                    $mailer->Body = '<!DOCTYPE html><html lang="ru"><head><meta charset="UTF-8"><title></title></head></body>'.
                                    $subscribe->mail_text.
                                    '<div style="margin-top: 15px; text-align: center">
                                    <a href="http://dominanta.loc/unsubscribe/'.$subscriber['hash'].'">Отписаться от рассылки</a>
                                    </div></body></html>';
                    $mailer->Subject = $subscribe->mail_subject;
                    $mailer->addCustomHeader('List-Unsubscribe', '<http://dominanta.loc/unsubscribe/'.$subscriber['hash'].'>');
                    $mailer->send();

                    SendedSubscribe::createAndSave([
                        'subscribe_id' => $subscribe->id,
                        'subscriber_id' => $subscriber['id'],
                    ]);
                } catch (Exception $e) {
                    SendedSubscribe::createAndSave([
                        'subscribe_id' => $subscribe->id,
                        'subscriber_id' => $subscriber['id'],
                        'send_errors' => date('[Y-m-d H:i:s]')." - ".$mailer->ErrorInfo."\n",
                    ]);
                }
            }

            $subscribe->status = Subscribe::STATUS_SENDED;
            $subscribe->save();
        }
    }
}