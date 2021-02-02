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

    
    public function __construct()
    {
        $this->mail = new PHPMailer();
        //Server settings
        //$this->mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
        $this->mail->isSMTP();                                            // Send using SMTP
        $this->mail->Host       = 'smtp.gmail.com';                    // Set the SMTP server to send through
        $this->mail->SMTPAuth   = true;                                   // Enable SMTP authentication
        $this->mail->Username   = 'donhang.simsodeptoanquoc.vn@gmail.com';                     // SMTP username
        $this->mail->Password   = 'yyrsbtzvsejvyvag';                               // SMTP password
        $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
        $this->mail->Port       = 587;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
        
    }

    public function send() {
        try {
            //Recipients
            $this->mail->setFrom('donhang.simsodeptoanquoc.vn@gmail.com', 'Automation mail'); //
            $this->mail->addAddress('quanly.simsodeptoanquoc.vn@gmail.com');     // Add a recipient

            $this->mail->isHTML(true);                                  // Set email format to HTML
            $this->mail->Subject = 'Mail Tự động';
            $this->mail->Body    = 'This is the HTML message body <b>in bold!</b>';
            $this->mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

            $this->mail->send();
            echo 'Message has been sent';
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$this->mail->ErrorInfo}";
        }
    }
}