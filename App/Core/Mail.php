<?php

namespace App\Core;

class Mail
{

  public function sendMailToNewUser($to)
  {
    $subject = 'Nouveau compte sur Arcadia';
    $headers = "Content-type: text/html; charset=UTF-8\r\n";
    $headers .= "From: arcadia@arcadia.com \r\n";

    $message = '<html>
                  <head>
                    <title>Calendrier des anniversaires pour Août</title>
                  </head>
                  <body style="font-family: Arial, Helvetica, sans-serif;
                      display: flex;
                      flex-direction: column;
                      align-items: center;
                      max-width: 800px;
                      width: 100%;
                      margin: 0 auto;">
                    <h1>Le compte test@test.com à été crée</h1>
                    <table>
                      <tr>
                        <p>Afin de pouvoir utiliser votre compte, vous devez vous approcher de l\'administrateur afin d\'avoir votre mot de passe.</p>
                      </tr>
                      <tr>
                      <img style="margin: 0 auto;" width="320px" class="mobile-menu__logo" src="https://localhost/assets/images/icons/arcadia-logo.svg" alt="">
                      </tr>
                    </table>
                  </body>
                </html>';

    $message = str_replace('test@test.com', $to, $message);


    if (!mail($to, $subject, $message, $headers)) {
      $_SESSION['error'] =  "Impossible d'envoyer l'email au nouvelle utilisateur";
    }
  }


  public function sendMailFromVisitor($message, $title, $email)
  {
    $headers = "Content-type: text/html; charset=UTF-8\r\n";
    $headers .= "From: contact@arcadia.com \r\n";

    return mail($email, $title, $message, $headers);
  }
}
