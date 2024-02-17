<?php

namespace App\Controller;

use App\Core\Router;
use App\Model\Advice;

class AdviceController extends Controller
{

  public function getAdvice($request)
  {
    $id  = filter_var($request['id'], FILTER_VALIDATE_INT);

    if (!is_int($id)) {
      http_response_code(400);
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
    }
  }

  public function getAdviceCount()
  {

    $adviceRepository = new Advice();
    $count =  $adviceRepository->count(['approved' => true]);

    if (isset($count)) {
      header('Content-Type: application/json');
      echo json_encode(['count' => $count]);
    } else {
      http_response_code(204);
    }
  }


  public function updateAdvice($request)
  {
  }

  public function insertAdvice()
  {
  }
}
