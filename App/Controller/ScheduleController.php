<?php

namespace App\Controller;

use App\Controller\Controller;
use App\Core\Security;
use App\Model\Schedule;

class ScheduleController extends Controller
{

  public function updateSchedule()
  {
    $csrf = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
    if (Security::verifyCsrf($csrf) && Security::isAdmin() && $_SERVER['REQUEST_METHOD'] === 'POST') {
      $content = trim(file_get_contents('php://input'));
      $data = json_decode($content, true);

      $id = htmlspecialchars($data['params']['id']);
      $open = htmlspecialchars($data['params']['open']);
      $close =  htmlspecialchars($data['params']['close']);

      if (empty($open) || empty($close)) {
        $open = null;
        $close = null;
      }
      $scheduleRepo = new Schedule();

      $scheduleRepo->update(['open' => $open, 'close' => $close], $id);
      echo json_encode(['success' => 'horaire mise à jour']);
    } else {
      http_response_code(401);

      if (!Security::verifyCsrf($csrf)) {
        echo json_encode(['error' => 'CSRF token is not valid']);
      } else {
        echo json_encode(['error' => 'accès interdit']);
      }
    }
  }
}
