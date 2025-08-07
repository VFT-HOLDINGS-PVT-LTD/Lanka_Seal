<?php
defined('BASEPATH') or exit('No direct script access allowed');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class Emailconfig_lib
{
    public $mail;

    public function __construct()
    {
        log_message('Debug', 'PHPMailer class is loaded.');

        // Include PHPMailer library files
        require 'vendor/phpmailer/phpmailer/src/Exception.php';
        require 'vendor/phpmailer/phpmailer/src/PHPMailer.php';
        require 'vendor/phpmailer/phpmailer/src/SMTP.php';

        $this->mail = new PHPMailer(true);
        $this->init_settings();
    }

    private function init_settings()
    {
        // try {
        //     $this->mail->isSMTP();
        //     $this->mail->Host = 'mail.hotelmate.lk'; // Changed to Gmail SMTP
        //     $this->mail->SMTPAuth = true;
        //     $this->mail->Username = 'test@hotelmate.lk'; // Update with your email
        //     $this->mail->Password = '43*!sdUsE8xx'; // Use Gmail App Password
        //     $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        //     $this->mail->Port = 465;
        //     $this->mail->isHTML(true);
        //     $this->mail->CharSet = 'UTF-8';
        //     $this->mail->setFrom('mail@vfthris.com', 'VFT Cloud');
        //     $this->mail->addReplyTo('noreply@webx.hrislkonline.com', 'No Reply');

        //     // Default sender
        //     $this->mail->setFrom('noreply@hotelmate.lk', 'OTP Test HotalMate');
        // } catch (Exception $e) {
        //     log_message('error', 'Mailer Error: ' . $e->getMessage());
        // }


        try {
            // Server settings
            $this->mail->isSMTP();
            $this->mail->Host = 'mail.hrislkonline.com';
            $this->mail->SMTPAuth = true;
            $this->mail->Username = 'noreply@webx.hrislkonline.com';
            $this->mail->Password = 'wxK]LSft*ED}';
            $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $this->mail->Port = 587;
            $this->mail->isHTML(true);
            $this->mail->CharSet = 'UTF-8';

            // Sender and recipient settings
            $this->mail->setFrom('mail@vfthris.com', 'VFT Cloud');
            $this->mail->addReplyTo('noreply@webx.hrislkonline.com', 'No Reply');
        } catch (Exception $e) {
            log_message('error', 'Mailer Error: ' . $e->getMessage());
        }
    }

    public function send_mail($to, $subject, $message, $attachments = array())
    {
        try {
            $this->mail->clearAddresses();
            $this->mail->clearAttachments();

            if (is_array($to)) {
                foreach ($to as $recipient) {
                    $this->mail->addAddress($recipient);
                }
            } else {
                $this->mail->addAddress($to);
            }

            $this->mail->Subject = $subject;
            $this->mail->Body = $message;

            if (!empty($attachments)) {
                foreach ($attachments as $attachment) {
                    if (file_exists($attachment)) {
                        $this->mail->addAttachment($attachment);
                    }
                }
            }

            return $this->mail->send();
        } catch (Exception $e) {
            log_message('error', 'Message could not be sent. Mailer Error: ' . $e->getMessage());
            return false;
        }
    }
}
