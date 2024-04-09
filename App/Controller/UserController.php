<?php

namespace App\Controller;

use App\Controller\Controller;
use App\Core\Exception\DatabaseException;
use App\Core\Exception\ValidatorException;
use App\Core\Router;
use App\Core\Security;
use App\Core\Validator;
use App\Model\User;
use Exception;

class UserController extends Controller
{

  public function table()
  {
    if (Security::isLogged()) {

      if (Security::isAdmin()) {
        $search = $_GET['search'] ?? '';
        $orderBy = $_GET['orderBy'] ?? 'id';
        $order = $_GET['order'] ?? 'asc';

        try {
          $page = $_GET['page'] ?? 1;
          $currentPage = $page;

          $userRepo = new User();
          $nbUsers = $userRepo->userCount($search);

          $userRepo->setLimit(10);
          $totalPage = ceil($nbUsers / $userRepo->getLimit());
          $first = ($currentPage - 1) * $userRepo->getLimit();

          $userRepo->setOffset($first);
          $users = $userRepo->fetchUsers($search, $orderBy, $order);

          $this->show('admin/user/table', [
            'params' => ['search' => $search, 'order' => $order],
            'users' => $users,
            'totalPages' => ($totalPage != 0) ?  $totalPage : 1,
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

  public function add()
  {
    if (Security::isLogged()) {

      if (Security::isAdmin()) {
        if ($_SERVER['REQUEST_METHOD'] === "POST") {
          $csrf = $_POST['csrf_token'] ?? '';
          if (Security::verifyCsrf($csrf)) {
            try {
              $email =  htmlspecialchars($_POST['email']);
              $password = htmlspecialchars($_POST['password']);
              $role = htmlspecialchars($_POST['role']);

              $email = trim($email);
              $email = strtolower($email);

              $userRepo = new User();

              // validate value from user
              Validator::strIsValideEmail($email);
              Validator::strLengthCorrect($password, 8, 60, 'Le mot de passe doit être en 8 et 60 caractères');
              Validator::strIsValidRole($role);

              $userRepo = new User();

              // Check if user already exists
              $user = $userRepo->findOneBy(['email' => $email]);
              if ($user) {
                throw new ValidatorException('un utilisateur avec cette adresse existe déjà');
              }

              $password =  Security::hashPassword($password);

              $userRepo->insert(['email' => $email, 'password' => $password, 'role' => $role]);

              $_SESSION['success'] = $email . ' à été crée';
              Router::redirect('dashboard/utilisateurs');
            } catch (Exception $e) {
              $_SESSION['error'] =  $e->getMessage();
            }
          } else {

            $_SESSION['error'] = 'Clé CSRF non valide';
          }
        }

        $this->show('admin/user/add');
      } else {
        $_SESSION['error'] = 'Vous n\'avez pas la permission';
        Router::redirect('dashboard');
      }
    } else {
      Router::redirect('login');
    }
  }
  public function edit($request)
  {
    if (Security::isLogged()) {
      if (Security::isAdmin()) {
        if ($_SERVER['REQUEST_METHOD'] === "POST") {
          $csrf = $_POST['csrf_token'] ?? '';
          if (Security::verifyCsrf($csrf)) {
            try {

              $id = htmlspecialchars($request['id']);
              $email =  htmlspecialchars($_POST['email']);
              $password = htmlspecialchars($_POST['password']);
              $role = htmlspecialchars($_POST['role']);
              $email = trim($email);
              $email = strtolower($email);

              $userRepo = new User();

              Validator::strIsInt($id);
              Validator::strIsValideEmail($email);
              Validator::strIsValidRole($role);

              if ($password != null) {
                Validator::strLengthCorrect($password, 8, 60, 'Le mot de passe doit être en 8 et 60 caractères');
              }

              $user = $userRepo->findOneBy(['email' => $email]);
              if ($user && $user->getId() != $id) {
                throw new ValidatorException('un utilisateur avec cette adresse existe déjà');
              }

              $user = $userRepo->findOneBy(['id' => $id]);

              if ($password != null) {
                $password =  Security::hashPassword($password);
                $userRepo->update(['email' => $email, 'password' => $password, 'role' => $role], $id);
              } else {
                $userRepo->update(['email' => $email,  'role' => $role], $id);
              }

              $_SESSION['success'] = $user->getEmail() . ' à été modifié';
              Router::redirect('dashboard/utilisateurs');
            } catch (Exception $e) {
              $_SESSION['error'] =  $e->getMessage();
            }
          } else {

            $_SESSION['error'] = 'Clé CSRF non valide';
          }
        }

        $userRepo = new User();
        $user = $userRepo->findOneBy(['id' => $request['id']]);
        $this->show('admin/user/edit', [
          'user' => $user,
        ]);
      } else {
        $_SESSION['error'] = 'Vous n\'avez pas la permission';
        Router::redirect('dashboard');
      }
    } else {
      Router::redirect('login');
    }
  }

  public function delete($request)
  {
    if (Security::isAdmin() && $_SERVER['REQUEST_METHOD'] === "POST") {
      $csrf = $_POST['csrf_token'] ?? '';
      if (Security::verifyCsrf($csrf)) {
        try {
          $id =  htmlspecialchars($request['id']);
          Validator::strIsInt($id);

          $userRepo = new User();
          $userRepo->delete(['id' => $id]);

          $_SESSION['success'] = 'l\'utilisateur à été supprimé';
        } catch (Exception $e) {
          $_SESSION['error'] =  $e->getMessage();
        }
      } else {
        $_SESSION['error'] = 'Clé CSRF pas valide';
      }
      Router::redirect('dashboard/utilisateurs');
    } else {
      $_SESSION['error'] = 'Vous n\'avez pas la permission';
      Router::redirect('dashboard');
    }
  }
}
