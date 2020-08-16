<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require '../libs/PHPMailer-master/src/Exception.php';
require '../libs/PHPMailer-master/src/PHPMailer.php';
require '../libs/PHPMailer-master/src/SMTP.php';

include_once '../config/database.php';
include_once '../objects/restaurant.php';

$database = new Database();
$db = $database->getConnection();

$restaurant = new Restaurant($db);

$restaurant->email = isset($_GET['email']) ? $_GET['email'] : die();
$token = isset($_GET['token']) ? $_GET['token'] : die();

$restaurant->readByEmail();

if ($restaurant->id == null) {
    http_response_code(400);
    echo "Etablissement inexistant";
} else {
    if ($restaurant->token == $token) {
        $mail = new PHPMailer(true);

        try {
            $message = '<html>
          <head>
           <title>RetroCov - Demande d\'un nouveau mot de passe</title>
          </head>
          <body style="background-color: lightgrey; padding: 50px;">
            <div style="width: 400px; margin: 0 auto; text-align: center; background-color: white;">
              <img src="http://retrocov.ch/RetroCov_Logo.png" width="200px" />
              <h2>Demande d\'un nouveau mot de passe.</h2>
                <a href="retrocov.ch/changepassword/'.$restaurant->email.'/'.$restaurant->token.'" style="text-decoration:none">Réinitialisez mon mot de passe</a>
              </div>
           </body>
         </html>';

            $mail->SMTPDebug = SMTP::DEBUG_SERVER;
            $mail->isSMTP();
            $mail->Host = 'mail.infomaniak.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'noreply@retrocov.ch';
            $mail->Password = 'Retro-2020-Cov';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('noreply@retrocov.ch', 'noreply@retrocov.ch');
            $mail->addAddress($restaurant->email, $restaurant->name);

            $mail->isHTML(true);
            $mail->Subject = "RetroCov - Demande d'un nouveau mot de passe";
            $mail->Body = $message;

            $mail->send();
            http_response_code(200);
            echo json_encode(array("message" => "Email has been successfuly send"));
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode(array("message" => "Unable to send the email."));
        }
    } else {
        http_response_code(400);
        echo json_encode(array("message" => "Unable to send the email."));
    }
}

/*
*/
