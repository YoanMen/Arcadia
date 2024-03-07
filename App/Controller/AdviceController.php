<?php

namespace App\Controller;

use App\Core\Router;
use App\Core\Security;
use App\Model\Advice;

class AdviceController extends Controller
{

  public function sendAdvice()
  {
    $csrf = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
    if (
      Security::verifyCsrf($csrf)
      && $_SERVER['REQUEST_METHOD'] ===  'POST'
    ) {

      $content = trim(file_get_contents("php://input"));

      $data = json_decode($content, true);

      echo json_encode($data);
    } else {
      http_response_code(401);
      echo json_encode(['error' => 'CSRF token is not valid']);
    }
  }

  public function getAdvice($request)
  {
    $csrf = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
    if (Security::verifyCsrf($csrf)) {

      try {
        $id  = filter_var($request['id'], FILTER_VALIDATE_INT);

        if (!is_int($id)) {
          http_response_code(400);
          echo json_encode(['error' => 'Is not a int']);
          return;
        }
        $adviceRepository = new Advice();
        $advice =  $adviceRepository->findOneBy(['id' => $id]);

        if (isset($advice)) {
          header('Content-Type: application/json');
          echo json_encode([
            'pseudo' => $advice->getPseudo(),
            'advice' => $advice->getAdvice()
          ]);
        } else {
          http_response_code(404);
          echo json_encode(['error' => 'Advice not found']);
        }
      } catch (\Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
      }
    } else {
      http_response_code(401);
      echo json_encode(['error' => 'CSRF token is not valid']);
    }
  }

  public function getAdviceCount()
  {
    $csrf = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
    if (Security::verifyCsrf($csrf)) {

      try {
        $adviceRepository = new Advice();
        $count =  $adviceRepository->count(['approved' => true]);

        if (isset($count)) {
          header('Content-Type: application/json');
          echo json_encode(['count' => $count]);
        } else {
          http_response_code(204);
          echo json_encode(['error' => 'No advice']);
        }
      } catch (\Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
      }
    } else {
      http_response_code(401);
      echo json_encode(['error' => 'CSRF token is not valid']);
    }
  }


  public function updateAdvice($request)
  {
  }

  public function insertAdvice()
  {
  }
}
