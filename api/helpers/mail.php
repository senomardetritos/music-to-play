<?php

require('../vendor/PHPMailer/src/PHPMailer.php');
require('../vendor/PHPMailer/src/SMTP.php');

class Mail
{

    protected $mail;

    function __construct()
    {
        $this->mail = new PHPMailer\PHPMailer\PHPMailer();
        $this->config();
    }

    private function config()
    {
        $this->mail->CharSet = "UTF-8";
        $this->mail->Encoding = 'base64';
        $this->mail->IsSMTP(); // enable SMTP 
        $this->mail->SMTPDebug = 1; // debugging: 1 = errors and messages, 2 = messages only 
        $this->mail->SMTPAuth = true; // authentication enabled 
        $this->mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for Gmail 
        $this->mail->Host = $_ENV['MAIL_HOST'];
        $this->mail->Port = $_ENV['MAIL_PORT'];
        $this->mail->IsHTML(true);
        $this->mail->Username = $_ENV['MAIL_USERNAME'];
        $this->mail->Password = $_ENV['MAIL_PASSWORD'];
        $this->mail->SetFrom("contato@senomalabs.com", 'SenomaLabs - Music to Play');
    }

    public function send($to, $subject, $body)
    {
        $this->mail->Subject = $subject;
        $this->mail->Body = $body;
        $this->mail->AddAddress($to);
        if (!$this->mail->Send()) {
            return ['error' => "Mailer Error: " . $this->mail->ErrorInfo];
            die();
        }
    }
}
