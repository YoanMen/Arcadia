<?php

namespace App\Core;

class Mail
{

  public function sendMailToNewUser($to)
  {
    $subject = 'Nouveau compte sur Arcadia';
    $headers = "MIME-Version: 1.0,\r\n";
    $headers .= "Content-type: text/html; charset=UTF-8\r\n";
    $headers .= "From: Zoo Arcadia <yoanmen@alwaysdata.net> \r\n";
    $headers .= "X-Mailer: PHP mail() \r\n";

    $message = <<<HTML
              <!DOCTYPE html>
              <html lang="fr">
              <head>
                  <meta charset="UTF-8">
                  <title>Nouveau compte sur Arcadia</title>
              </head>
              <body style="font-family: Arial, Helvetica, sans-serif;">
                  <h1>Le compte <strong> test@test.com </strong> a été crée</h1>
                  <table>
                      <tr>
                         <p>Afin de pouvoir utiliser votre compte, vous devez vous approcher de l'administrateur afin d'avoir votre mot de passe.</p>
                      </tr>
                  </table>
              </body>
              </html>
              HTML;

    $message = str_replace('test@test.com', $to, $message);

    if (!mail($to, $subject, $message, $headers)) {
      $_SESSION['error'] =  "Impossible d'envoyer l'email au nouvel utilisateur";
    }
  }


  public function sendMailFromVisitor($message, $title, $email)
  {
    $headers = "Content-type: text/html; charset=UTF-8\r\n";
    $headers .= "From: test@test.com \r\n";
    $headers = str_replace('test@test.com', $email, $headers);

    return mail(MAIL, $title, $message, $headers);
  }
}
