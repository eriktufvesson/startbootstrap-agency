<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../vendor/autoload.php';

$config['displayErrorDetails'] = true;
$config['addContentLengthHeader'] = false;

// $config['db']['host']   = "localhost";
// $config['db']['user']   = "dressbyheart";
// $config['db']['pass']   = "secret"; // afexq43gq53!ds
// $config['db']['dbname'] = "dressbyheart_slim";
$config['db']['host']   = "185.76.64.172";
$config['db']['user']   = "tyllnsro_dressbyheart";
$config['db']['pass']   = "afexq43gq53!ds"; // 
$config['db']['dbname'] = "tyllnsro_dressbyheart";

$app = new \Slim\App(['settings' => $config]);

$container = $app->getContainer();

$container['logger'] = function($c) {
  $logger = new \Monolog\Logger('api');
  $file_handler = new \Monolog\Handler\StreamHandler("../logs/app.log");
  $logger->pushHandler($file_handler);
  return $logger;
};

$container['db'] = function ($c) {
  $db = $c['settings']['db'];
  $pdo = new PDO("mysql:host=" . $db['host'] . ";dbname=" . $db['dbname'],
      $db['user'], $db['pass']);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
  return $pdo;
};

$app->get('/', function(Request $request, Response $response) {
  $response->getBody()->write("DBH Api");
  // $this->logger->addInfo("Something interesting happened");
  return $response;
});

$app->get('/event/{event_name}', function (Request $request, Response $response) {
  $event_name = $request->getAttribute('event_name');

  $sth = $this->db->prepare("SELECT * FROM event WHERE name = :event_name");
  $sth->bindParam('event_name', $event_name);
  $sth->execute();
  $event = $sth->fetchObject();
  return $this->response->withJson($event);
});

$app->get('/event/places_left/{event_id}', function (Request $request, Response $response) {
  $event_id = $request->getAttribute('event_id');

  $sth = $this->db->prepare("select 
      max_participants, 
      (select sum(nbr_places) from event_registration where event_id = event.id and queue = 0 and cancelled = 0) as registrations
    from event where id = :event_id");
  $sth->bindParam('event_id', $event_id);
  $sth->execute();
  $data = $sth->fetchObject();
  $places_left = intval($data->max_participants) - intval($data->registrations);
  return $this->response->withJson(array('places_left' => $places_left));
});

$app->post('/event/register', function (Request $request, Response $response) {
  $registration = $request->getParsedBody();

  $event_id = $registration['event_id'];
  $email = $registration['email'];
  $name = $registration['name'];
  $nbr_places = intval($registration['nbr_places']);
  $queue = 0;

  // Get event info
  $sth = $this->db->prepare("SELECT * FROM event WHERE id = :event_id");
  $sth->bindParam('event_id', $event_id);
  $sth->execute();
  $event = $sth->fetchObject();

  // Check that email has not registered
  $sth = $this->db->prepare("select count(*) as cnt from event_registration where event_id = :event_id and email = :email");
  $sth->bindParam('event_id', $event_id);
  $sth->bindParam('email', $email);
  $sth->execute();
  $data = $sth->fetchObject();
  if (intval($data->cnt) > 0) {
    return $this->response->withJson(array('status' => false, 'message' => 'Du kan endast skicka in en anmälan per e-postadress.'));
  } 

  // Check if event is full and place in queue
  $sth = $this->db->prepare("select 
    max_participants,
    (select sum(nbr_places) from event_registration where event_id = event.id and queue = 0 and cancelled = 0) as registrations
    from event where id = :event_id");
  $sth->bindParam('event_id', $event_id);
  $sth->execute();
  $data = $sth->fetchObject();
  $places_left = intval($data->max_participants) - intval($data->registrations);
  if ($places_left === 0) {
    $queue = 1;
  }
  $registration['queue'] = $queue;

  // Insert registration
  $sth = $this->db->prepare("insert into event_registration (event_id, email, name, nbr_places, queue)
    values (:event_id, :email, :name, :nbr_places, :queue)");
  $sth->bindParam('event_id', $event_id);
  $sth->bindParam('email', $email);
  $sth->bindParam('name', $name);
  $sth->bindParam('nbr_places', $nbr_places);
  $sth->bindParam('queue', $queue);
  $sth->execute();

  $registration['id'] = $this->db->lastInsertId();

  // Send confimation email
  if (!$queue) {
    sendEmail('info@dressbyheart.se', 'Dress by heart', $email, $name, "Anmälan till inspirationsföreläsning i Lund $event->date", 'anmalan.html');
  }
  else {
    // Send queue email instead
    sendEmail('info@dressbyheart.se', 'Dress by heart', $email, $name, "Anmälan till inspirationsföreläsning i Lund $event->date", 'anmalan-queue.html');
  }

  // Send email to admin
  sendEmail('no-reply@dressbyheart.se', 'Dress by heart', 'info@dressbyheart.se', '', 'Anmälan inspirationsföreläsning ' . $event->name, "Namn: $name\nE-postadress: $email\nAntal platser: $nbr_places\Kö: $queue");

  return $this->response->withJson(array('status' => true, 'registration' => $registration));

});

$app->get('/event/registrations/{event_id}', function (Request $request, Response $response) {
  $event_id = $request->getAttribute('event_id');

  $sth = $this->db->prepare("select * from event_registration where event_id = :event_id");
  $sth->bindParam('event_id', $event_id);
  $sth->execute();
  $data = $sth->fetchAll();
  return $this->response->withJson($data);
});

$app->run();

function sendEmail($from_email, $from_name, $to_email, $to_name, $subject, $body, $attachment = NULL) {
  $status = FALSE;
  
  $mail = new PHPMailer();
  $mail->CharSet = 'UTF-8';
  
  $mail->setFrom($from_email, $from_name);
  $mail->addAddress($to_email, $to_name);
  $mail->Subject = $subject;
  if (endsWith($body, '.html')) {
    $mail->msgHTML(file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/mail/' . $body), dirname(__FILE__));
  }
  else {
    $mail->Body = $body;
  }
  //Attach a file
  if ($attachment) {
    $mail->addAttachment($_SERVER['DOCUMENT_ROOT'] . '/mail/attachments/' . $attachment);
  }
  //send the message, check for errors
  if (!$mail->send()) {
    $status = FALSE;
  } else {
    $status = TRUE;
  }
  return $status;
}

function endsWith($haystack, $needle)
{
    $length = strlen($needle);

    return $length === 0 || 
    (substr($haystack, -$length) === $needle);
}