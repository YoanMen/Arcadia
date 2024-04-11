<?php

namespace App\Controller;

use App\Controller\Controller;
use App\Core\Exception\DatabaseException;
use App\Core\Router;
use App\Core\Security;
use App\Model\Schedule;
use Exception;

class ScheduleController extends Controller
{

  public function table()
  {
    if (Security::isLogged()) {

      if (Security::isAdmin()) {
        try {

          $scheduleRepo = new Schedule();


          $data = $scheduleRepo->fetchAll();

          $this->show('admin/schedule/table', [
            'schedules' => $data,

          ]);
        } catch (Exception $e) {
          throw new DatabaseException($e);
        }
      } else {
        $_SESSION['error'] = 'Vous n\'avez pas la permission';
        Router::redirect('dashboard');
      }
    } else {
      Router::redirect('login');
    }
  }


  public function updateSchedule()
  {

    if (Security::isAdmin()) {
      if ($_SERVER['REQUEST_METHOD'] === "PUT") {

        $content = trim(file_get_contents('php://input'));
        $data = json_decode($content, true);

        $id = htmlspecialchars($data['id']);
        $open = htmlspecialchars($data['open']);
        $close =  htmlspecialchars($data['close']);
        $csrf = htmlspecialchars($data['csrf_token']);

        if (Security::verifyCsrf($csrf)) {
          try {
            if (empty($open) || empty($close)) {
              $open = null;
              $close = null;
            }
            $scheduleRepo = new Schedule();

            $scheduleRepo->update(['open' => $open, 'close' => $close], $id);
            echo json_encode(['success' =>  'horaire modifié']);
          } catch (Exception $e) {
            http_response_code(500);
            $_SESSION['error'] =  $e->getMessage();
            echo json_encode(['error' =>  $e->getMessage()]);
          }
        } else {

          $_SESSION['error'] = 'Clé CSRF non valide';
          http_response_code(500);
          echo json_encode(['error' => 'Clé CSRF non valide']);
        }
      }
    } else {
      http_response_code(401);
      echo json_encode(['error' => 'accès interdit']);
    }
  }
}
