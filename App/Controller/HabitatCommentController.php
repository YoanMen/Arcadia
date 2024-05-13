<?php

namespace App\Controller;

use App\Core\Exception\DatabaseException;
use App\Core\Exception\ValidatorException;
use App\Core\Router;
use App\Core\Security;
use App\Core\Validator;
use App\Model\Habitat;
use App\Model\HabitatComment;
use App\Model\Veterinary;
use Exception;

class HabitatCommentController extends Controller
{
  public function table()
  {
    if (Security::isLogged()) {

      if (Security::isVeterinary() || Security::isAdmin()) {
        $search = $_GET['search'] ?? '';
        $order = $_GET['order'] ?? 'desc';
        $orderBy = $_GET['orderBy'] ?? 'updated_at';

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
              $habitat = htmlspecialchars($_POST['habitat']);
              $comment = htmlspecialchars($_POST['comment']);

              $comment = ltrim($comment);

              Validator::isNull($habitat, 'Animal ne peut pas être nulle');
              Validator::strMinLengthCorrect($comment, 3);


              if (empty($habitat)) {
                throw new ValidatorException('Aucun habitat sélectionné');
              }
              $veterinary = new Veterinary();

              $userId = Security::getCurrentUserId();


              // update or insert new table if $habitatComment exist or not
              $veterinary->commentHabitat($userId, $habitat, $comment);

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
