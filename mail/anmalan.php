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
   
$date = $_GET['date'];
$time = $_GET['time'];

// // Create the email and send the message to site owner
// $to = 'info@dressbyheart.se'; // Add your email address inbetween the '' replacing yourname@yourdomain.com - This is where the form will send a message to.
// $email_subject = "Garderoben form submission: $name";
// $email_body = "Name: $name\n\nEmail: $email_address";
// $headers = "From: noreply@dressbyheart.se"; // This is the email address the generated message will be from. We recommend using something like noreply@yourdomain.com.
// $ok = mail($to,$email_subject,$email_body,$headers);

// // Create the email and send the message to the user
// $to = $email_address; // Add your email address inbetween the '' replacing yourname@yourdomain.com - This is where the form will send a message to.
// $email_subject = "Garderobsgenomgång";
// $email_body = "Hej, \n\nTack för ditt intresse i guiden till garderobsgenomgång. Du finner den bifogad!\n\nMvh Jane - Dress by heart";
// $headers = "From: info@dressbyheart.se"; // This is the email address the generated message will be from. We recommend using something like noreply@yourdomain.com.
// $ok = mail($to,$email_subject,$email_body,$headers);

$status = FALSE;

$mail = new PHPMailer();
$mail->CharSet = 'UTF-8';

$mail->setFrom('info@dressbyheart.se', 'Dress by heart');
//Set an alternative reply-to address
// $mail->addReplyTo($email_address);
//Set who the message is to be sent to
$mail->addAddress($email_address, $name);
//Set the subject line
$mail->Subject = "Anmälan till inspirationsföreläsning i Lund $date klockan $time";
//Read an HTML message body from an external file, convert referenced images to embedded,
//convert HTML into a basic plain-text alternative body
$mail->msgHTML(file_get_contents('anmalan.html'), dirname(__FILE__));
//Attach a file
#$mail->addAttachment($_SERVER['DOCUMENT_ROOT'] . '/mail/attachments/garderoben-dressbyheart.pdf');
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
    $mail2->Subject = "Anmälan inspirationsföreläsning $date";
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
