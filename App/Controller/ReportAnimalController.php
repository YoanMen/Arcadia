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
        $content = trim(file_get_contents("php://input"));
        $data = json_decode($content, true);

        $search = htmlspecialchars($data['search']);
        $order = htmlspecialchars($data['order']);
        $orderBy = htmlspecialchars($data['orderBy']);
        $date = htmlspecialchars($data['date']);

        $animaReportRepo = new ReportAnimal();
        $reports = $animaReportRepo->fetchReportAnimal($search, $date, $order, $orderBy);


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
