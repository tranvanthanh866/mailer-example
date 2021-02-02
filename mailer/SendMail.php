<?php
// Load Composer's autoloader
require 'vendor/autoload.php';
// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class SendMail {

    protected $mail;

    
    public function __construct($usermail, $passmail)
    {
        $this->mail = new PHPMailer();
        //Server settings
        //$this->mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
        $this->mail->isSMTP();                                            // Send using SMTP
        $this->mail->Host       = 'smtp.gmail.com';                    // Set the SMTP server to send through
        $this->mail->SMTPAuth   = true;                                   // Enable SMTP authentication
        $this->mail->Username   = $usermail; //'donhang.simsodeptoanquoc.vn@gmail.com';                     // SMTP username
        $this->mail->Password   = $passmail; //'yyrsbtzvsejvyvag';                               // SMTP password
        $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
        $this->mail->Port       = 587;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
        
    }

    public function send($subject, $content, $to, $from) {
        try {
            //Recipients
            $this->mail->setFrom($from, 'Automation mail'); //
            $this->mail->addAddress($to);     // Add a recipient

            $this->mail->isHTML(true);                                  // Set email format to HTML
            $this->mail->Subject = $subject;
            $this->mail->Body    = $content;
            //$this->mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

            $this->mail->send();
            return true;
        } catch (Exception $e) {
            return false;
            //echo "Message could not be sent. Mailer Error: {$this->mail->ErrorInfo}";
        }
    }
}