<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

/**
 * Class MailerModule
 */
class MailerModule {
    protected $fromAddress;
    protected $fromName;

    /**
     * @param $to
     * @param $subject
     * @param $message
     * @return bool
     */
    public function send($to, $subject, $message)
    {
        try {
            $mail = new PHPMailer(true);

            //Server settings
            $mail->SMTPDebug = SMTP::DEBUG_OFF;                      // Disable debug message output
            $mail->isSMTP();                                            // Send using SMTP
            $mail->Host       = env('SMTP_HOST');                    // Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
            $mail->Username   = env('SMTP_USERNAME');                     // SMTP username
            $mail->Password   = env('SMTP_PASSWORD');                               // SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
            $mail->Port       = 587;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

            //Set encoding
            $mail->CharSet = 'utf-8';

            //Recipients
            $mail->setFrom($this->fromAddress, $this->fromName);
            $mail->addAddress($to);     // Add a recipient
            $mail->addReplyTo($this->fromAddress, $this->fromName);

            // Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = $subject;
            $mail->Body    = $message;

            $mail->send();

            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * @param $to
     * @param $subject
     * @param $message
     * @return bool
     */
    public static function sendMail($to, $subject, $message)
    {
        try {
            $mailer = new self();
            $mailer->fromAddress = env('SMTP_FROMADDRESS');
            $mailer->fromName = env('SMTP_FROMNAME');
            return $mailer->send($to, $subject, $message);
        } catch (\Exception $e) {
            return false;
        }
    }
}
