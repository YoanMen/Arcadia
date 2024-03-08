<?php

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
$router->addRoute('POST', ROOT . '/api/sendAdvice', 'HomeController', 'sendAdvice');

$router->addRoute('GET', ROOT . '/services', 'ServiceController', 'index');
$router->addRoute('GET', ROOT . '/services/{name}', 'ServiceController', 'showService');
$router->addRoute('GET', ROOT . '/habitats', 'HabitatController', 'index');
$router->addRoute('GET', ROOT . '/habitats/{name}', 'HabitatController', 'showHabitat');
$router->addRoute('GET', ROOT . '/habitats/{name}/{animalName}', 'HabitatController', 'showAnimal');

$router->addRoute('GET', ROOT . '/upload', 'UploadController', 'index');
$router->addRoute('POST', ROOT . '/upload', 'UploadController', 'uploadFile');

$router->addRoute('GET', ROOT . '/test/{paramName}', 'HomeController', 'withParams');

$router->addRoute('GET', ROOT . '/login', 'AuthController', 'login');
$router->addRoute('POST', ROOT . '/login', 'AuthController', 'login');
$router->addRoute('GET', ROOT . '/logout', 'AuthController', 'logout');

$router->addRoute('GET', ROOT . '/dashboard', 'AuthController', 'index');

$router->addRoute('GET', ROOT . '/api/initmenu', 'HomeController', 'initMenu');
$router->addRoute('GET', ROOT . '/api/advice/count', 'AdviceController', 'getAdviceCount');
$router->addRoute('POST', ROOT . '/api/advice/send', 'AdviceController', 'sendAdvice');
$router->addRoute('GET', ROOT . '/api/advice/{id}', 'AdviceController', 'getAdvice');

$router->goRoute($router);
