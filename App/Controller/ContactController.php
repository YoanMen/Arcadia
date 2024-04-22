<?php

namespace App\Controller;

use App\Core\Mail;
use App\Core\Security;
use App\Core\Validator;
use Exception;

class ContactController extends Controller
{

  public function index()
  {

    $csrf = $_POST['csrf_token'] ?? '';

    if ($_SERVER['REQUEST_METHOD'] === "POST") {

      try {
        if (Security::verifyCsrf($csrf)) {

          $email = htmlspecialchars($_POST['email']);
          $title = htmlspecialchars($_POST['title']);
          $message = htmlspecialchars($_POST['message']);

          Validator::strIsValideEmail($email);
          Validator::strLengthCorrect($title, 2, 40, 'Le titre doit être entre 2 et 40 caractères');
          Validator::strMinLengthCorrect($message, 1, 'Le message ne peux pas être vide');

          $mail = new Mail();

          if ($mail->sendMailFromVisitor($message, $title, $email)) {
            $_SESSION['success'] = 'Votre message à bien été envoyé, nous y répondrons le plus rapidement possible';
          } else {
            $_SESSION['error'] = 'Impossible d\'envoyer votre message une erreur interne est survenue';
          }
        } else {
          $_SESSION['error'] = 'La clé CSRF n\'est pas valide';
        }
      } catch (Exception $e) {
        $_SESSION['error'] =  $e->getMessage();
      }
    }

    $this->show('contact');
  }
}
