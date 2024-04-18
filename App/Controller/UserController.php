<?php

namespace App\Controller;

use App\Controller\Controller;
use App\Core\Exception\DatabaseException;
use App\Core\Router;
use App\Core\Security;
use App\Core\Validator;
use App\Model\Admin;
use App\Model\User;
use Exception;

class UserController extends Controller
{
  private User $user;

  public function __construct()
  {
    $this->user = new User();
  }
  public function table()
  {
    if (Security::isLogged()) {

      if (Security::isAdmin()) {
        $search = $_GET['search'] ?? '';
        $orderBy = $_GET['orderBy'] ?? 'id';
        $order = $_GET['order'] ?? 'desc';

        try {
          $page = $_GET['page'] ?? 1;
          $currentPage = $page;

          $nbUsers = $this->user->userCount($search);

          $this->user->setLimit(10);
          $totalPages = ceil($nbUsers / $this->user->getLimit());
          $first = ($currentPage - 1) * $this->user->getLimit();

          $this->user->setOffset($first);
          $users = $this->user->fetchUsers($search, $orderBy, $order);

          $this->show('admin/user/table', [
            'params' => ['search' => $search, 'order' => $order],
            'users' => $users,
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

              // validate value from user
              Validator::strIsValideEmail($email);
              Validator::strLengthCorrect($password, 8, 60, 'Le mot de passe doit être en 8 et 60 caractères');
              Validator::strIsValidRole($role);

              $admin = new Admin();

              $admin->createUser($email, $password, $role);

              $_SESSION['success'] = $email . ' à été crée';
              Router::redirect('dashboard/utilisateurs');
            } catch (Exception $e) {
              $_SESSION['error'] =  $e->getMessage();
            }
          } else {

            $_SESSION['error'] = 'Clé CSRF non valide';
          }
        }

        $this->show(
          'admin/user/add',
          [
            'email' => $email ?? '',
            'password' => $password ?? '',
            'role' => $role ?? ''
          ]
        );
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

              Validator::strIsInt($id);
              Validator::strIsValideEmail($email);
              Validator::strIsValidRole($role);

              if ($password != null) {
                Validator::strLengthCorrect($password, 8, 60, 'Le mot de passe doit être en 8 et 60 caractères');
              }

              $admin = new Admin();

              $admin->updateUser($id, $email, $password, $role);

              $_SESSION['success'] = 'L\'utilisateur à été modifié';
              Router::redirect('dashboard/utilisateurs');
            } catch (Exception $e) {
              $_SESSION['error'] =  $e->getMessage();
            }
          } else {

            $_SESSION['error'] = 'Clé CSRF non valide';
          }
        }

        $user = $this->user->findOneBy(['id' => $request['id']]);
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

          $admin = new Admin();

          $admin->deleteUser($id);

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
