<?php

namespace App\Controller;

use App\Core\Exception\DatabaseException;
use App\Core\Security;
use App\Model\Habitat;
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

        $search = htmlspecialchars($data['params']['search']);
        $order = htmlspecialchars($data['params']['order']);
        $orderBy = htmlspecialchars($data['params']['orderBy']);
        $date = htmlspecialchars($data['params']['date']);
        $count = htmlspecialchars($data['params']['count']);

        $animaReportRepo = new ReportAnimal();
        $habitatRepo = new Habitat();

        $habitats = $habitatRepo->fetchAllHabitatsWithoutComment();
        $reportCount = $animaReportRepo->fetchReportAnimalCount($search, $date);
        $remainCount = $reportCount - $count;

        if ($remainCount > 0) {
          $animaReportRepo->setOffset($count);
          $reports = $animaReportRepo->fetchReportAnimal($search, $date, $order, $orderBy);
          echo json_encode(['data' => $reports, 'habitats' => $habitats, 'totalCount' => $reportCount]);
        } else {
          throw new DatabaseException("aucun résultat");
        }
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
