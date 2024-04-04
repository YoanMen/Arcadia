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
$router->addRoute('POST', ROOT . '/api/sendAdvice', 'HomeController', 'sendAdvice');

$router->addRoute('GET', ROOT . '/services', 'ServiceController', 'index');
$router->addRoute('GET', ROOT . '/services/{name}', 'ServiceController', 'showService');
$router->addRoute('GET', ROOT . '/habitats', 'HabitatController', 'index');
$router->addRoute('GET', ROOT . '/habitats/{name}', 'HabitatController', 'showHabitat');
$router->addRoute('GET', ROOT . '/habitats/{name}/{animalName}', 'HabitatController', 'showAnimal');

$router->addRoute('GET', ROOT . '/upload', 'UploadController', 'index');

$router->addRoute('GET', ROOT . '/test/{paramName}', 'HomeController', 'withParams');

$router->addRoute('GET', ROOT . '/login', 'AuthController', 'index');
$router->addRoute('POST', ROOT . '/login', 'AuthController', 'login');
$router->addRoute('GET', ROOT . '/logout', 'AuthController', 'logout');

$router->addRoute('GET', ROOT . '/dashboard', 'AuthController', 'index');


//API
$router->addRoute('GET', ROOT . '/api/initmenu', 'HomeController', 'initMenu');


$router->addRoute('POST', ROOT . '/api/habitats', 'HabitatController', 'getHabitats');
$router->addRoute('POST', ROOT . '/api/habitats/comment', 'HabitatCommentController', 'getHabitatsComment');
$router->addRoute('POST', ROOT . '/api/habitats/uploadImage', 'HabitatController', 'uploadImage');
$router->addRoute('POST', ROOT . '/api/habitats/images', 'HabitatController', 'getHabitatImages');
$router->addRoute('DELETE', ROOT . '/api/habitats/images', 'HabitatController', 'deleteImage');
$router->addRoute('POST', ROOT . '/api/habitats/create', 'HabitatController', 'createHabitat');
$router->addRoute('POST', ROOT . '/api/habitats/update', 'HabitatController', 'updateHabitat');
$router->addRoute('DELETE', ROOT . '/api/habitats', 'HabitatController', 'deleteHabitat');

$router->addRoute('POST', ROOT . '/api/animals', 'AnimalController', 'getAnimals');
$router->addRoute('POST', ROOT . '/api/animals/report', 'ReportAnimalController', 'getReportAnimal');
$router->addRoute('POST', ROOT . '/api/animals/images', 'AnimalController', 'getAnimalImages');
$router->addRoute('POST', ROOT . '/api/animals/uploadImage', 'AnimalController', 'uploadImage');
$router->addRoute('POST', ROOT . '/api/animals/create', 'AnimalController', 'createAnimal');
$router->addRoute('POST', ROOT . '/api/animals/update', 'AnimalController', 'updateAnimal');
$router->addRoute('DELETE', ROOT . '/api/animals', 'AnimalController', 'deleteAnimal');
$router->addRoute('DELETE', ROOT . '/api/animals/images', 'AnimalController', 'deleteImage');
$router->addRoute('POST', ROOT . '/api/animals/habitats', 'AnimalController', 'getAnimalsByHabitat');

$router->addRoute('POST', ROOT . '/api/services', 'ServiceController', 'getServices');
$router->addRoute('DELETE', ROOT . '/api/services', 'ServiceController', 'deleteService');
$router->addRoute('POST', ROOT . '/api/services/update', 'ServiceController', 'updateService');
$router->addRoute('POST', ROOT . '/api/services/create', 'ServiceController', 'createService');

$router->addRoute('POST', ROOT . '/api/users', 'UserController', 'getUsers');
$router->addRoute('DELETE', ROOT . '/api/users', 'UserController', 'deleteUser');
$router->addRoute('POST', ROOT . '/api/users/update', 'UserController', 'updateUser');
$router->addRoute('POST', ROOT . '/api/users/create', 'UserController', 'createUser');

$router->addRoute('GET', ROOT . '/api/role', 'AuthController', 'getRole');

$router->addRoute('POST', ROOT . '/api/schedules', 'ScheduleController', 'updateSchedule');

$router->addRoute('POST', ROOT . '/api/advices/update', 'AdviceController', 'updateAdvice');
$router->addRoute('POST', ROOT . '/api/advices', 'AdviceController', 'getAdvices');
$router->addRoute('GET', ROOT . '/api/advices/approved', 'AdviceController', 'getApprovedAdvices');
$router->addRoute('POST', ROOT . '/api/advice/send', 'AdviceController', 'sendAdvice');

$router->addRoute('POST', ROOT . '/api/food', 'FoodAnimalController', 'getFoods');
$router->addRoute('POST', ROOT . '/api/food/create', 'FoodAnimalController', 'createFood');


// DASHBOARD NAVIGATION
$router->addRoute('GET', ROOT . '/dashboard/habitat', 'AuthController', 'loadHabitatPage');
$router->addRoute('GET', ROOT . '/dashboard/animal', 'AuthController', 'loadAnimalPage');
$router->addRoute('GET', ROOT . '/dashboard/dashboard', 'AuthController', 'loadDashboardPage');
$router->addRoute('GET', ROOT . '/dashboard/service', 'AuthController', 'loadServicePage');
$router->addRoute('GET', ROOT . '/dashboard/food', 'AuthController', 'loadFoodAnimalPage');

$router->addRoute('GET', ROOT . '/dashboard/user', 'AuthController', 'loadUserPage');
$router->addRoute('GET', ROOT . '/dashboard/schedule', 'AuthController', 'loadSchedulePage');
$router->addRoute('GET', ROOT . '/dashboard/advice', 'AuthController', 'loadAdvicePage');

$router->goRoute($router);
