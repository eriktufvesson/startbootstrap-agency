<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../vendor/autoload.php';
setlocale(LC_TIME, 'sv_SE');

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
  $event->formattedDate = getFormattedDate($event->date);
  $event->enableBooking = strtotime($event->date) > time();
  $event->next = getNextEvent($event->id, $this->db);
  return $this->response->withJson($event);
});

function getFormattedDate($date) {
  return strftime('%e:e %B', strtotime($date));
}

function getNextEvent($id, $db) {
  $sth = $db->prepare("SELECT * FROM event WHERE date > (SELECT date from event where id = :id) AND date >= now() ORDER BY date LIMIT 1");
  $sth->bindParam('id', $id);
  $sth->execute();
  $event = $sth->fetchObject();
  if ($event) {
    $event->formattedDate = getFormattedDate($event->date);
  }
  return $event;
}

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

$app->post('/giftcert/buy', function (Request $request, Response $response) {
  $order = $request->getParsedBody();

  $giftcert_id = $order['giftcert_id'];
  $delivery_id = $order['delivery_id'];
  $email = $order['email'];
  $name = $order['name'];
  $address = $order['address'];
  $postal_code = $order['postal_code'];
  $city = $order['city'];
  $message = $order['message'];
  
  // Get giftcert
  $sth = $this->db->prepare("SELECT * FROM giftcert WHERE id = :giftcert_id");
  $sth->bindParam('giftcert_id', $giftcert_id);
  $sth->execute();
  $giftcert = $sth->fetchObject();

  // Get delivery method
  $sth = $this->db->prepare("SELECT * FROM delivery_method WHERE id = :delivery_id");
  $sth->bindParam('delivery_id', $delivery_id);
  $sth->execute();
  $delivery_method = $sth->fetchObject();

  // Create order
  $payment_method = '';
  $subtotal = $giftcert->price;

  $order_query = 'insert into `order` (giftcert_id, delivery_method, name, email, address, postal_code, city, payment_method, subtotal, message) values (:giftcert_id, :delivery_method, :name, :email, :address, :postal_code, :city, :payment_method, :subtotal, :message)';
  $sth = $this->db->prepare($order_query);
  $sth->bindParam('giftcert_id', $giftcert_id);
  $sth->bindParam('delivery_method', $delivery_method->name);
  $sth->bindParam('name', $name);
  $sth->bindParam('email', $email);
  $sth->bindParam('address', $address);
  $sth->bindParam('postal_code', $postal_code);
  $sth->bindParam('city', $city);
  $sth->bindParam('payment_method', $payment_method);
  $sth->bindParam('subtotal', $subtotal);
  $sth->bindParam('message', $message); 
  $sth->execute();

  $order_id = $this->db->lastInsertId();
  $order['id'] = $order_id;

  // Send confimation email
  $replacement_array = array(
    '#ORDERNR#' => $order['id'],
    '#GIFTCERT_NAME#' => $giftcert->name,
    '#GIFTCERT_PRICE#' => $giftcert->price,
    '#DELIVERY_METHOD#' => $delivery_method->name,
    '#NAME#' => $name,
    '#EMAIL#' => $email,
    '#ADDRESS#' => $address,
    '#POSTAL_CODE#' => $postal_code,
    '#CITY#' => $city,
    '#PAYMENT_METHOD#' => $payment_method,
    '#SUBTOTAL#' => $subtotal,
    '#MESSAGE#' => str_replace('\n', '<br>', $message),
  );
  sendEmail('info@dressbyheart.se', 'Dress by heart', $email, $name, "Orderbekräftelse presentkort. Ordernr: $order_id", 'presentkort.html', $replacement_array);

  // Send email to admin
  sendEmail('no-reply@dressbyheart.se', 'Dress by heart', 'info@dressbyheart.se', '', 'Ny beställning av presentkort. Ordernr: ' . $order_id, 
    "Presentkort: $giftcert->name\nPris: $giftcert->price\nLeveranssätt: $delivery_method->name\nNamn: $name\nE-postadress: $email\nAdress: $address\nPostadress: $postal_code $city\nMeddelande: $message\n\nTotalt: $subtotal\n");

  return $this->response->withJson(array('status' => true, 'order' => $order));

});

$app->post('/competition', function (Request $request, Response $response) {
  $post_data = $request->getParsedBody();

  $email = $post_data['email'];
  $name = $post_data['name'];
  $answer = $post_data['answer'];
  
  // Send email to admin
  sendEmail('no-reply@dressbyheart.se', 'Dress by heart', 'info@dressbyheart.se', '', 'Inskickat tävlingssvar', 
    "Namn: $name\nE-postadress: $email\nSvar: $answer\n");

  return $this->response->withJson(array('status' => true));

});

$app->run();

function sendEmail($from_email, $from_name, $to_email, $to_name, $subject, $body, $replacement_array = NULL, $attachment = NULL) {
  $status = FALSE;
  
  $mail = new PHPMailer();
  $mail->CharSet = 'UTF-8';
  
  $mail->setFrom($from_email, $from_name);
  $mail->addAddress($to_email, $to_name);
  $mail->Subject = $subject;
  if (endsWith($body, '.html')) {
    $html = file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/mail/' . $body);
    if ($replacement_array) {
      $html = replaceValues($html, $replacement_array);
    }
    error_log($html);
    $mail->msgHTML($html, dirname(__FILE__));
  }
  else {
    if ($replacement_array) {
      $body = replaceValues($body, $replacement_array);
    }
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

function replaceValues($text, $replacement_array) {
  foreach ($replacement_array as $key => $value) {
    $text = str_replace($key, $value, $text);
  }
  return $text;
}

function endsWith($haystack, $needle)
{
    $length = strlen($needle);

    return $length === 0 || 
    (substr($haystack, -$length) === $needle);
}