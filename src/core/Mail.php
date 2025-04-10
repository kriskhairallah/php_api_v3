<?php

namespace App\Core;

use PHPMailer\PHPMailer\PHPMailer;

class Mail
{
    private static function createMailer(): PHPMailer
    {
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = $_ENV['SMTP_HOST'];
        $mail->SMTPAuth = true;
        $mail->Username = $_ENV['SMTP_USERNAME'];
        $mail->Password = $_ENV['SMTP_PASSWORD'];
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = (int)$_ENV['SMTP_PORT'];
        $mail->setFrom($_ENV['MAIL_FROM_ADDRESS'], $_ENV['MAIL_FROM_NAME']);
        
        return $mail;
    }

    public static function sendVerification(string $to, string $token): void
    {
        $mail = self::createMailer();
        $verifyUrl = $_ENV['APP_URL'] . "/verify-email?token=" . $token;
        
        $mail->addAddress($to);
        $mail->isHTML(true);
        $mail->Subject = 'Verify your email address';
        $mail->Body = "Please click this link to verify your email: <a href='$verifyUrl'>Verify Email</a>";
        
        $mail->send();
    }

    public static function sendPasswordReset(string $to, string $token): void
    {
        $mail = self::createMailer();
        $resetUrl = $_ENV['APP_URL'] . "/reset-password?token=" . $token;
        
        $mail->addAddress($to);
        $mail->isHTML(true);
        $mail->Subject = 'Reset your password';
        $mail->Body = "Please click this link to reset your password: <a href='$resetUrl'>Reset Password</a>";
        
        $mail->send();
    }
}