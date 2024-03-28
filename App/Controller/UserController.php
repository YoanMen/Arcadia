<?php

namespace App\Controller;

use App\Controller\Controller;
use App\Core\Exception\DatabaseException;
use App\Core\Exception\ValidatorException;
use App\Core\Security;
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

        $email = strtolower($email);

        $this->ValidateValues($email, $password, $role);

        $userRepo = new User();

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

        $email =  htmlspecialchars($data['params']['email']);
        $password = htmlspecialchars($data['params']['password']);
        $role = htmlspecialchars($data['params']['role']);
        $id = htmlspecialchars($data['params']['id']);


        $this->ValidateValues($email, $password, $role, $id);

        $userRepo = new User();

        $email = strtolower($email);
        $password =  Security::hashPassword($password);

        $userRepo->update(['email' => $email, 'password' => $password, 'role' => $role], $id);
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

  public function ValidateValues(string $email, string $password, string $role, int $id = null)
  {
    // verification of user values
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      throw new ValidatorException('adresse email non valide');
    }

    if (!(strlen($password) >= 8 && strlen($password) <= 60)) {
      throw new ValidatorException('Le mot de passe doit être entre 8 et 60 caractères');
    }

    if ($role !== 'employee' && $role !== 'veterinary') {
      throw new ValidatorException('Le nom du rôle n\'est pas valide');
    }

    $userRepo = new User();

    if (isset($id) && !is_int($id)) {
      throw new ValidatorException('ID doit être un int');
    }
    if (!isset($id)) {
      if ($userRepo->findOneBy(['email' => $email])) {
        throw new ValidatorException('un utilisateur avec cette adresse existe déjà');
      }
    } else {
      $user = $userRepo->findOneBy(['email' => $email]);
      if ($user && $user->getId() != $id) {
        throw new ValidatorException('un utilisateur avec cette adresse existe déjà');
      }
    }
  }
}
