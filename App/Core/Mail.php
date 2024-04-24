<?php

namespace App\Core;

class Mail
{

  public function sendMailToNewUser($to)
  {
    $subject = 'Nouveau compte sur Arcadia';
    $headers = "Content-type: text/html; charset=UTF-8\r\n";
    $headers .= "From: yoanmen@alwaysdata.net \r\n";

    $message = <<<HTML
                <!DOCTYPE html>
                <html lang="fr">
                <head>
                    <meta charset="UTF-8">
                    <title>Nouveau compte sur Arcadia</title>
                </head>
                <body style="font-family: Arial, Helvetica, sans-serif;
                      display: flex;
                      flex-direction: column;
                      align-items: center;
                      max-width: 800px;
                      width: 100%;
                      margin: 0 auto;">
                    <h1>Le compte <strong> test@test.com </strong> à été crée</h1>
                    <p>Afin de pouvoir utiliser votre compte, vous devez vous approcher de l'administrateur afin d'avoir votre mot de passe.</p>
                    <img style="margin: 0 auto;" width="128px" src="https://yoanmen.alwaysdata.net/assets/images/icons/arcadia-logo.svg" alt="icon zoo">
                </body>
                </html>
                HTML;

    $message = str_replace('test@test.com', $to, $message);

    if (!mail($to, $subject, $message, $headers)) {
      $_SESSION['error'] =  "Impossible d'envoyer l'email au nouvelle utilisateur";
    }
  }


  public function sendMailFromVisitor($message, $title, $email)
  {
    $headers = "Content-type: text/html; charset=UTF-8\r\n";
    $headers .= "From: test@test.com \r\n";
    $headers = str_replace('test@test.com', $email, $headers);

    return mail("yoanmen@alwaysdata.net", $title, $message, $headers);
  }
}
