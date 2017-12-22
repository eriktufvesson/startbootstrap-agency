<?php

require_once('../vendor/autoload.php');

// Check for empty fields
if(empty($_POST['name'])      ||
   empty($_POST['email'])     ||
   !filter_var($_POST['email'],FILTER_VALIDATE_EMAIL))
   {
   echo "No arguments Provided!";
   return false;
   }
   
$name = strip_tags(htmlspecialchars($_POST['name']));
$email_address = strip_tags(htmlspecialchars($_POST['email']));

$status = FALSE;

$mail = new PHPMailer();
$mail->CharSet = 'UTF-8';

$mail->setFrom('info@dressbyheart.se', 'Dress by heart');
//Set an alternative reply-to address
// $mail->addReplyTo($email_address);
//Set who the message is to be sent to
$mail->addAddress($email_address, $name);
//Set the subject line
$mail->Subject = 'Nyårsutmaning - Må bra i dina kläder';
//Read an HTML message body from an external file, convert referenced images to embedded,
//convert HTML into a basic plain-text alternative body
$mail->msgHTML(file_get_contents('nyar.html'), dirname(__FILE__));
//Attach a file
$mail->addAttachment($_SERVER['DOCUMENT_ROOT'] . '/mail/attachments/garderoben-dressbyheart.pdf');
$mail->addAttachment($_SERVER['DOCUMENT_ROOT'] . '/mail/attachments/nyarsutmaning-dressbyheart.pdf');
//send the message, check for errors
if (!$mail->send()) {
    echo "Mailer Error: " . $mail->ErrorInfo;
    $status = FALSE;
} else {
    echo "Message sent!";
    $status = TRUE;
}

if ($status) {
    $mail2 = new PHPMailer();
    $mail2->CharSet = 'UTF-8';
    
    $mail2->setFrom('noreply@dressbyheart.se', 'Dress by heart');
    $mail2->addReplyTo($email_address);
    $mail2->addAddress('info@dressbyheart.se');
    //Set the subject line
    $mail2->Subject = 'Nyårsutmaning - ny deltagare';
    //Read an HTML message body from an external file, convert referenced images to embedded,
    //convert HTML into a basic plain-text alternative body
    $mail2->Body = "Namn: $name\nE-postadress: $email_address";
    if (!$mail2->send()) {
        echo "Mailer Error: " . $mail2->ErrorInfo;
        $status = FALSE;
    } else {
        echo "Message sent!";
        $status = TRUE;
    }
}

return $status;
?>
