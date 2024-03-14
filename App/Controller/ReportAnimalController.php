<?php

namespace App\Controller;

use App\Core\Security;
use App\Model\ReportAnimal;
use Exception;

class ReportAnimalController extends Controller
{

  public function getReportAnimal()
  {
    $csrf = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';

    if (Security::verifyCsrf($csrf) && $_SERVER['REQUEST_METHOD'] === "POST") {
      try {
        $animaReportRepo = new ReportAnimal();
        $reports = $animaReportRepo->fetchReportAnimal();


        echo json_encode(['data' => $reports]);
      } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
      }
    } else {
      http_response_code(401);
      echo json_encode(['error' => 'CSRF token is not valid']);
    }
  }

  public function getReportAnimalByName()
  {
    $csrf = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';

    if (Security::verifyCsrf($csrf) && $_SERVER['REQUEST_METHOD'] === "POST") {
      try {
        $content = trim(file_get_contents("php://input"));
        $data = json_decode($content, true);
        $search = htmlspecialchars($data['search']);

        $animaReportRepo = new ReportAnimal();
        $reports = $animaReportRepo->fetchReportAnimalByName($search);


        if ($reports == null) {
          http_response_code(204);
        }

        echo json_encode(['data' => $reports]);
      } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
      }
    } else {
      http_response_code(401);
      echo json_encode(['error' => 'CSRF token is not valid']);
    }
  }
}
