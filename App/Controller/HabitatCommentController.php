<?php

namespace App\Controller;

use App\Core\Exception\DatabaseException;
use App\Core\Security;
use App\Model\HabitatComment;
use Exception;

class HabitatCommentController extends Controller
{
  public function getHabitatsComment()
  {
    $csrf = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
    if (Security::verifyCsrf($csrf) && Security::isAdmin() && $_SERVER['REQUEST_METHOD'] === 'POST') {
      try {

        $content = trim(file_get_contents('php://input'));
        $data = json_decode($content, true);

        $search = htmlspecialchars($data['params']['search']);
        $order = htmlspecialchars($data['params']['order']);
        $orderBy = htmlspecialchars($data['params']['orderBy']);
        $count = htmlspecialchars($data['params']['count']);

        $habitatsCommentRepo = new HabitatComment();

        $habitatCount = $habitatsCommentRepo->habitatsCommentCount($search);
        $remainCount = $habitatCount - $count;

        if ($remainCount > 0) {
          $habitatsCommentRepo->setOffset($count);
          $habitatsComment = $habitatsCommentRepo->fetchHabitatsComment($search, $order, $orderBy);

          echo json_encode(['data' => $habitatsComment, 'totalCount' => $habitatCount]);
        } else {
          throw new DatabaseException('aucun résultat');
        }
      } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
      }
    } else {

      http_response_code(401);
      if (!Security::verifyCsrf($csrf)) {
        echo json_encode(['error' => 'CSRF token is not valid']);
      } else {
        echo json_encode(['error' => 'Permission refusé']);
      }
    }
  }
}
