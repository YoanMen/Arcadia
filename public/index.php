<?php

use App\Core\CouchDB;
use App\Core\Router as Router;

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
$router->addRoute('GET', ROOT . '/habitats/{name}/{animalName}', 'AnimalController', 'showAnimal');

$router->addRoute('GET', ROOT . '/login', 'AuthController', 'login');
$router->addRoute('POST', ROOT . '/login', 'AuthController', 'login');
$router->addRoute('GET', ROOT . '/logout', 'AuthController', 'logout');

//API
$router->addRoute('GET', ROOT . '/api/initmenu', 'HomeController', 'initMenu');
$router->addRoute('POST', ROOT . '/api/advices/send', 'AdviceController', 'sendAdvice');
$router->addRoute('GET', ROOT . '/api/advices/approved', 'AdviceController', 'getApprovedAdvices');
$router->addRoute('PUT', ROOT . '/api/advices', 'AdviceController', 'updateAdvice');

$router->addRoute('PUT', ROOT . '/api/schedules', 'ScheduleController', 'updateSchedule');
$router->addRoute('DELETE', ROOT . '/api/habitats/images', 'HabitatController', 'deleteImage');
$router->addRoute('GET', ROOT . '/api/habitats/{id}/animals', 'HabitatController', 'getAnimalsOfHabitat');
$router->addRoute('POST', ROOT . '/api/habitats/images', 'HabitatController', 'uploadImage');
$router->addRoute('POST', ROOT . '/api/animals/images', 'AnimalController', 'uploadImage');
$router->addRoute('DELETE', ROOT . '/api/animals/images', 'AnimalController', 'deleteImage');

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

$router->addRoute('GET', ROOT . '/dashboard/animaux', 'AnimalController', 'table');
$router->addRoute('GET', ROOT . '/dashboard/animaux/add', 'AnimalController', 'add');
$router->addRoute('POST', ROOT . '/dashboard/animaux/add', 'AnimalController', 'add');
$router->addRoute('GET', ROOT . '/dashboard/animaux/{id}/edit', 'AnimalController', 'edit');
$router->addRoute('POST', ROOT . '/dashboard/animaux/{id}/edit', 'AnimalController', 'edit');
$router->addRoute('POST', ROOT . '/dashboard/animaux/{id}/delete', 'AnimalController', 'delete');

$router->addRoute('GET', ROOT . '/dashboard/services', 'ServiceController', 'table');
$router->addRoute('GET', ROOT . '/dashboard/services/add', 'ServiceController', 'add');
$router->addRoute('POST', ROOT . '/dashboard/services/add', 'ServiceController', 'add');
$router->addRoute('GET', ROOT . '/dashboard/services/{id}/edit', 'ServiceController', 'edit');
$router->addRoute('POST', ROOT . '/dashboard/services/{id}/edit', 'ServiceController', 'edit');
$router->addRoute('POST', ROOT . '/dashboard/services/{id}/delete', 'ServiceController', 'delete');

$router->addRoute('GET', ROOT . '/dashboard/alimentation-animaux', 'FoodAnimalController', 'table');
$router->addRoute('GET', ROOT . '/dashboard/alimentation-animaux/{id}/detail', 'FoodAnimalController', 'detail');
$router->addRoute('GET', ROOT . '/dashboard/alimentation-animaux/add', 'FoodAnimalController', 'add');
$router->addRoute('POST', ROOT . '/dashboard/alimentation-animaux/add', 'FoodAnimalController', 'add');

$router->addRoute('GET', ROOT . '/dashboard/rapport-animaux', 'ReportAnimalController', 'table');
$router->addRoute('GET', ROOT . '/dashboard/rapport-animaux/{id}/detail', 'ReportAnimalController', 'detail');
$router->addRoute('GET', ROOT . '/dashboard/rapport-animaux/add', 'ReportAnimalController', 'add');
$router->addRoute('POST', ROOT . '/dashboard/rapport-animaux/add', 'ReportAnimalController', 'add');

$router->addRoute('GET', ROOT . '/dashboard/commentaire-habitats', 'HabitatCommentController', 'table');
$router->addRoute('GET', ROOT . '/dashboard/commentaire-habitats/{id}/detail', 'HabitatCommentController', 'detail');
$router->addRoute('GET', ROOT . '/dashboard/commentaire-habitats/add', 'HabitatCommentController', 'add');
$router->addRoute('POST', ROOT . '/dashboard/commentaire-habitats/add', 'HabitatCommentController', 'add');

$router->addRoute('GET', ROOT . '/dashboard/avis', 'AdviceController', 'table');
$router->addRoute('GET', ROOT . '/dashboard/avis/{id}/detail', 'AdviceController', 'detail');

$router->addRoute('GET', ROOT . '/dashboard/horaires', 'ScheduleController', 'table');



$router->goRoute($router);
