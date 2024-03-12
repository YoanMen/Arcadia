<?php

namespace App\Controller;

use App\Core\Security;
use App\Model\HabitatComment;
use Exception;

class HabitatCommentController extends Controller
{
  public function getHabitatsComment()
  {
    $csrf = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
    $order = $_SERVER['HTTP_ORDER'] ?? '';
    if (Security::verifyCsrf($csrf) && $_SERVER['REQUEST_METHOD'] === 'POST') {
      try {
        $habitatsCommentRepo = new HabitatComment();
        $habitatsComment = $habitatsCommentRepo->fetchHabitatsComment($order);

        echo json_encode(['data' => $habitatsComment]);
      } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
      }
    } else {
      http_response_code(401);
      echo json_encode(['error' => 'CSRF token is not valid']);
    }
  }

  public function getHabitatsCommentByName()
  {
    $csrf = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
    if (Security::verifyCsrf($csrf) && $_SERVER['REQUEST_METHOD'] === 'POST') {
      try {
        $content = trim(file_get_contents("php://input"));
        $data = json_decode($content, true);
        $search = htmlspecialchars($data['search']);


        $habitatsCommentRepo = new HabitatComment();
        $habitatsComment = $habitatsCommentRepo->getHabitatsCommentByName($search);

        if ($habitatsComment == null) {
          http_response_code(204);
        }

        echo json_encode(['data' => $habitatsComment]);
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
