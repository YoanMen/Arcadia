<?php

namespace App\Controller;

use App\Core\Exception\DatabaseException;
use App\Core\Exception\ValidatorException;
use App\Core\Security;
use App\Core\Validator;
use App\Model\Habitat;
use App\Model\HabitatComment;
use Exception;

class HabitatCommentController extends Controller
{
  public function getHabitatsComment()
  {
    $csrf = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
    if (Security::verifyCsrf($csrf) && !Security::isEmployee() && $_SERVER['REQUEST_METHOD'] === 'POST') {
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

        $habitatRepo = new Habitat();
        $habitats = $habitatRepo->fetchAllHabitatsWithoutComment();

        if ($remainCount > 0) {
          $habitatsCommentRepo->setOffset($count);
          $habitatsComment = $habitatsCommentRepo->fetchHabitatsComment($search, $order, $orderBy);

          echo json_encode(['data' => $habitatsComment, 'totalCount' => $habitatCount, 'habitats' =>  $habitats]);
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

  public function createComment()
  {
    $csrf = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';

    if (Security::verifyCsrf($csrf) && Security::isVeterinary() && $_SERVER['REQUEST_METHOD'] === "POST") {
      try {
        $content = trim(file_get_contents('php://input'));
        $data = json_decode($content, true);

        $userId = Security::getCurrentUserId();
        $habitatId = htmlspecialchars($data['params']['habitat_id']);
        $comment = htmlspecialchars($data['params']['comment']);


        Validator::strIsInt($habitatId);
        Validator::strIsInt($userId);
        Validator::strMinLengthCorrect($comment, 3);

        if ($habitatId == 0) {
          throw new ValidatorException('Sélectionner un habitat');
        }

        $habitatCommentRepo = new HabitatComment();

        $commentHabitat = $habitatCommentRepo->find(['habitatId' => $habitatId]);

        if ($commentHabitat) {
          $habitatCommentRepo->updateComment($habitatId, $userId, $comment);
        } else {
          $habitatCommentRepo->insert(['comment' => $comment, 'userId' => $userId, 'habitatId' => $habitatId]);
        }
        echo json_encode(['success' => 'l/`animal à été crée']);
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
