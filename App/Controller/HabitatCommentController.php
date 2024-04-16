<?php

namespace App\Controller;

use App\Core\Exception\DatabaseException;
use App\Core\Exception\ValidatorException;
use App\Core\Router;
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

        $habitatCount = $habitatsCommentRepo->countHabitatComment($search);
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

  public function table()
  {
    if (Security::isLogged()) {

      if (Security::isVeterinary() || Security::isAdmin()) {
        $search = $_GET['search'] ?? '';
        $order = $_GET['order'] ?? 'desc';
        $orderBy = $_GET['orderBy'] ?? 'habitat';

        try {
          $page = $_GET['page'] ?? 1;
          $currentPage = $page;
          $habitatCommentRepo = new HabitatComment();
          $nbHabitatComment = $habitatCommentRepo->countHabitatComment($search);

          $habitatCommentRepo->setLimit(10);
          $totalPages = ceil($nbHabitatComment / $habitatCommentRepo->getLimit());
          $first = ($currentPage - 1) * $habitatCommentRepo->getLimit();

          $habitatCommentRepo->setOffset($first);


          $habitatComments = $habitatCommentRepo->fetchHabitatsComment($search, $order, $orderBy);


          $this->show('admin/habitatComment/table', [
            'params' => ['search' => $search, 'order' => $order],
            'habitatComments' => $habitatComments,
            'totalPages' => ($totalPages != 0) ?  $totalPages : 1,
            'currentPage' =>  $currentPage,
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

  public function detail($request)
  {

    if (Security::isLogged()) {

      if ($_SERVER['REQUEST_METHOD'] == "GET" && (Security::isAdmin() || Security::isVeterinary())) {

        try {
          $id = htmlspecialchars($request['id']);

          Validator::strIsInt($id);

          $habitatCommentRepo = new HabitatComment();

          $habitatComment = $habitatCommentRepo->findHabitatCommentById($id);

          if ($habitatComment) {

            $this->show('admin/habitatComment/detail', [
              'habitatComment' => $habitatComment
            ]);
          } else {
            $_SESSION['error'] = 'aucun commentaire pour cette habitat';
            Router::redirect('dashboard/commentaire-habitats');
          }
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

  public function add()
  {
    if (Security::isLogged()) {

      if (Security::isVeterinary()) {
        if ($_SERVER['REQUEST_METHOD'] === "POST") {
          $csrf = $_POST['csrf_token'] ?? '';
          if (Security::verifyCsrf($csrf)) {
            try {
              $userId = Security::getCurrentUserId();

              $habitat = htmlspecialchars($_POST['habitat']);
              $comment = htmlspecialchars($_POST['comment']);

              $comment = ltrim($comment);

              Validator::isNull($habitat, 'Animal ne peut pas être nulle');
              Validator::strMinLengthCorrect($comment, 3);


              if (empty($habitat)) {
                throw new ValidatorException('Aucun habitat sélectionné');
              }

              $habitatCommentRepo = new HabitatComment();
              $habitatComment = $habitatCommentRepo->findOneBy(['habitatId' => $habitat]);

              // update or insert new table if $habitatComment exist or not
              if ($habitatComment) {

                $habitatCommentRepo->updateComment($habitat, $userId, $comment);
              } else {
                $habitatCommentRepo->insert([
                  'userId' => $userId, 'habitatId' => $habitat,
                  'comment' => $comment
                ]);
              }

              $_SESSION['success'] = 'Le rapport sur l\'habitat à été crée';
              Router::redirect('dashboard/commentaire-habitats');
            } catch (Exception $e) {
              $_SESSION['error'] =  $e->getMessage();
            }
          } else {

            $_SESSION['error'] = 'Clé CSRF non valide';
          }
        }

        $habitatRepo = new Habitat();
        $habitats = $habitatRepo->fetchAllHabitatsWithoutComment();


        $this->show('admin/habitatComment/add', [
          'habitat' => $habitat ?? '',
          'comment' => $comment ?? '',
          'habitats' => $habitats
        ]);
      } else {
        $_SESSION['error'] = 'Vous n\'avez pas la permission';
        Router::redirect('dashboard');
      }
    } else {
      Router::redirect('login');
    }
  }
}
