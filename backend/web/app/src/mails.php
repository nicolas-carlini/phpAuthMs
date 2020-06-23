<?php
// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
// Load Composer's autoloader
require 'vendor/autoload.php';



class mailer
{
  // Instantiation and passing `true` enables exceptions
  function __construct()
  {
    try {
      $this->mail = new PHPMailer(true);
      //Server settings
      $this->mail->SMTPDebug = 0;                      // Enable verbose debug output
      $this->mail->isSMTP();                                            // Send using SMTP
      $this->mail->Host       = 'smtp1.gmail.com';                    // Set the SMTP server to send through
      $this->mail->SMTPAuth   = true;                                   // Enable SMTP authentication
      $this->mail->Username   = 'CARLINI.NOREPLY@gmail.com';                     // SMTP username
      $this->mail->Password   = 'pepe2020';                               // SMTP password
      $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
      $this->mail->Port       = 587;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
    } catch (Exception $e) {
      echo $e;
    }
  }

  function send()
  {
    try {
      //Recipients
      $this->mail->setFrom('from@example.com', 'Mailer');
      $this->mail->addAddress('joe@example.net', 'Joe User');     // Add a recipient
      $this->mail->addReplyTo('info@example.com', 'Information');
      $this->mail->addCC('cc@example.com');
      $this->mail->addBCC('bcc@example.com');

      // Content
      $this->mail->isHTML(true);                                  // Set email format to HTML
      $this->mail->Subject = 'Here is the subject';
      $this->mail->Body    = 'This is the HTML message body <b>in bold!</b>';
      $this->mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

      $this->mail->send();

      return true;
    } catch (Exception $e) {
      echo $e;
      return false;
    }
  }
}
