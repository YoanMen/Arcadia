<?php

namespace App\Controller;

use App\Core\Router;
use App\Model\Advice;

class AdviceController extends Controller
{
  public function index()
  {
  }


  public function getAdvice($request)
  {
    $id = $request['id'];

    $adviceRepository = new Advice();

    $advice =  $adviceRepository->findOneBy(['id' => $id]);

    if (isset($advice)) {
      header('Content-Type: application/json');
      echo json_encode([
        'pseudo' => $advice->getPseudo(),
        'advice' => $advice->getAdvice()
      ]);
    } else {
      throw new \Exception('No advice found ');
    }
  }

  public function getAdviceCount()
  {

    $adviceRepository = new Advice();

    $count =  $adviceRepository->count(['approved' => true]);

    header('Content-Type: application/json');
    echo json_encode(['count' => $count]);
  }


  public function updateAdvice($request)
  {
  }

  public function insertAdvice()
  {
  }
}
