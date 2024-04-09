<?php

use App\Core\Router as Router;
use App\Model\Animal;

require_once "../App/Core/Autoloader.php";

session_start();

if (!isset($_SESSION['csrf_token'])) {
  $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}


DEBUG ? ini_set('display_errors', 1) : ini_set('display_errors', 0);



// ROUTING
$router = new Router;
$router->addRoute('GET', ROOT . '/error', 'PageNotFoundController', 'index');

$router->addRoute('GET', ROOT . '/', 'HomeController', 'index');
$router->addRoute('GET', ROOT . '/services', 'ServiceController', 'index');
$router->addRoute('GET', ROOT . '/services/{name}', 'ServiceController', 'showService');
$router->addRoute('GET', ROOT . '/habitats', 'HabitatController', 'index');
$router->addRoute('GET', ROOT . '/habitats/{name}', 'HabitatController', 'showHabitat');
$router->addRoute('GET', ROOT . '/habitats/{name}/{animalName}', 'HabitatController', 'showAnimal');

$router->addRoute('GET', ROOT . '/login', 'AuthController', 'index');
$router->addRoute('POST', ROOT . '/login', 'AuthController', 'login');
$router->addRoute('GET', ROOT . '/logout', 'AuthController', 'logout');

//API
$router->addRoute('GET', ROOT . '/api/initmenu', 'HomeController', 'initMenu');
$router->addRoute('POST', ROOT . '/api/advices/send', 'AdviceController', 'sendAdvice');
$router->addRoute('GET', ROOT . '/api/advices/approved', 'AdviceController', 'getApprovedAdvices');
$router->addRoute('DELETE', ROOT . '/api/habitats/images', 'HabitatController', 'deleteImage');
$router->addRoute('POST', ROOT . '/api/habitats/images', 'HabitatController', 'uploadImage');


$router->addRoute('GET', ROOT . '/dashboard', 'AuthController', 'index');

$router->addRoute('GET', ROOT . '/dashboard/utilisateurs', 'UserController', 'table');
$router->addRoute('GET', ROOT . '/dashboard/utilisateurs/add', 'UserController', 'add');
$router->addRoute('POST', ROOT . '/dashboard/utilisateurs/add', 'UserController', 'add');
$router->addRoute('GET', ROOT . '/dashboard/utilisateurs/{id}/edit', 'UserController', 'edit');
$router->addRoute('POST', ROOT . '/dashboard/utilisateurs/{id}/edit', 'UserController', 'edit');
$router->addRoute('POST', ROOT . '/dashboard/utilisateurs/{id}/delete', 'UserController', 'delete');

$router->addRoute('GET', ROOT . '/dashboard/habitats', 'HabitatController', 'table');
$router->addRoute('GET', ROOT . '/dashboard/habitats/add', 'HabitatController', 'add');
$router->addRoute('POST', ROOT . '/dashboard/habitats/add', 'HabitatController', 'add');
$router->addRoute('GET', ROOT . '/dashboard/habitats/{id}/edit', 'HabitatController', 'edit');
$router->addRoute('POST', ROOT . '/dashboard/habitats/{id}/edit', 'HabitatController', 'edit');
$router->addRoute('POST', ROOT . '/dashboard/habitats/{id}/delete', 'HabitatController', 'delete');

$router->goRoute($router);
