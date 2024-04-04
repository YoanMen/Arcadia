<?php

namespace App\Controller;

use App\Controller\Controller;
use App\Core\Exception\DatabaseException;
use App\Core\Exception\ValidatorException;
use App\Core\Security;
use App\Core\Validator;
use App\Model\User;
use Exception;

class UserController extends Controller
{
  public function getUsers()
  {
    $csrf = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? "";

    if (Security::isAdmin() && Security::verifyCsrf($csrf) && $_SERVER['REQUEST_METHOD'] === 'POST') {
      try {
        $content = trim(file_get_contents('php://input'));
        $data = json_decode($content, true);

        // get all params
        $search = htmlspecialchars($data['params']['search']);
        $order = htmlspecialchars($data['params']['order']);
        $orderBy = htmlspecialchars($data['params']['orderBy']);
        $count = htmlspecialchars($data['params']['count']);

        $userRepo = new User();

        $userCount = $userRepo->userCount($search);
        $remainCount = $userCount - $count;

        if ($remainCount > 0) {
          $userRepo->setOffset($count);
          $users = $userRepo->fetchUsers($search, $order, $orderBy);
          echo json_encode(['data' => $users, 'totalCount' => $userCount]);
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
        echo json_encode(['error' => 'accès interdit']);
      }
    }
  }

  public function createUser()
  {
    $csrf = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';

    if (Security::verifyCsrf($csrf) && Security::isAdmin() && $_SERVER['REQUEST_METHOD'] == 'POST') {

      try {
        $content = trim(file_get_contents('php://input'));
        $data = json_decode($content, true);

        $email =  htmlspecialchars($data['params']['email']);
        $password = htmlspecialchars($data['params']['password']);
        $role = htmlspecialchars($data['params']['role']);

        $email = trim($email);
        $email = strtolower($email);

        Validator::strIsValideEmail($email);
        Validator::strLengthCorrect($password, 8, 60);
        Validator::strIsValidRole($role);

        $userRepo = new User();

        $user = $userRepo->findOneBy(['email' => $email]);
        if ($user) {
          throw new ValidatorException('un utilisateur avec cette adresse existe déjà');
        }

        $password =  Security::hashPassword($password);

        $userRepo->insert(['email' => $email, 'password' => $password, 'role' => $role]);
        echo json_encode(['success' => 'l\'utilisateur à été crée']);
      } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
      }
    } else {
      http_response_code(401);

      if (!Security::verifyCsrf($csrf)) {
        echo json_encode(['error' => 'CSRF token is not valid']);
      } else {
        echo json_encode(['error' => 'accès interdit']);
      }
    }
  }

  public function updateUser()
  {
    $csrf = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';

    if (Security::verifyCsrf($csrf) && Security::isAdmin() && $_SERVER['REQUEST_METHOD'] == 'POST') {

      try {
        $content = trim(file_get_contents('php://input'));
        $data = json_decode($content, true);

        $id = htmlspecialchars($data['params']['id']);
        $email =  htmlspecialchars($data['params']['email']);
        $password = htmlspecialchars($data['params']['password']);
        $role = htmlspecialchars($data['params']['role']);

        $email = trim($email);
        $email = strtolower($email);

        $userRepo = new User();

        Validator::strIsInt($id);
        Validator::strIsValideEmail($email);
        Validator::strLengthCorrect($password, 8, 60);
        Validator::strIsValidRole($role);

        $user = $userRepo->findOneBy(['email' => $email]);
        if ($user && $user->getId() != $id) {
          throw new ValidatorException('un utilisateur avec cette adresse existe déjà');
        }

        $user = $userRepo->findOneBy(['id' => $id]);

        // change password if is not the same
        if ($password !==  $user->getPassword()) {
          $password =  Security::hashPassword($password);
          $userRepo->update(['email' => $email, 'password' => $password, 'role' => $role], $id);
        }

        $userRepo->update(['email' => $email,  'role' => $role], $id);
        echo json_encode(['success' => 'l\'utilisateur à été modifié']);
      } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
      }
    } else {
      http_response_code(401);

      if (!Security::verifyCsrf($csrf)) {
        echo json_encode(['error' => 'CSRF token is not valid']);
      } else {
        echo json_encode(['error' => 'accès interdit']);
      }
    }
  }

  public function deleteUser()
  {
    $csrf = $_SERVER['HTTP_X_CSRF_TOKEN'];

    if (Security::verifyCsrf($csrf) && Security::isAdmin() && $_SERVER['REQUEST_METHOD'] === 'DELETE') {

      try {
        $content = trim(file_get_contents('php://input'));
        $data = json_decode($content, true);

        $id = htmlspecialchars($data['params']['id']);

        $userRepo = new User();
        $userRepo->delete(['id' => $id]);

        echo json_encode(['success' => 'le service à été supprimé']);
      } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
      }
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
