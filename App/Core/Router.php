<?php

namespace App\Core;

class Router
{
  private $routes;
  public function __construct()
  {
    $this->routes = [];
  }


  public static function redirect($path = '')
  {
    header("Location: " . ROOT . "/" . $path);
    die;
  }
  public function goRoute($router)
  {
    // Get the HTTP method and URI
    $method = $_SERVER['REQUEST_METHOD'];
    $uri = BASE_URL . $_SERVER['REQUEST_URI'];

    // Find the appropriate route for the given HTTP method and URI
    $getRoute = $router->getRoute($method, $uri);

    // If no matching route was found, redirect to an error page
    if ($getRoute == null) {
      $this->redirect('error');
    }

    // Instantiate the appropriate controller for the matched route
    $controller = new $getRoute['controller']();

    // Call the appropriate action method of the controller, passing any necessary parameters from the matched route
    $action = $getRoute['action'];
    $controller->$action($getRoute['params']);
  }
  public function addRoute(string $method, string $path, string $controller, string $action)
  {
    $this->routes[] = [
      'method' => $method,
      'path' => $path,
      'controller' => 'App\Controller\\' . $controller,
      'action' => $action,
    ];
  }

  // Define a private method for getting a route based on HTTP method and URI path
  private function getRoute(string $method, string $uri): ?array
  {
    // Loop through all routes defined in the application
    foreach ($this->routes as $route) {
      $routeParts = explode('/', $route['path']);
      $uriParts = explode('/', $uri);
      $count = count($uriParts) - 1;

      // Explode path and count to match the URI path with route paths
      if (str_contains($uriParts[$count], '?')) {
        $clean = strstr($uriParts[$count], '?', true);
        $uriParts[$count] = $clean;
      }
      // Check if the current route matches
      if (
        $route['method'] === $method && count($routeParts) === count($uriParts)
      ) {

        // If the current route matches, extract any parameters from the current route definition and the incoming request URI path
        $params = [];
        $paramName = null;
        $match = true;

        foreach ($routeParts as $index => $part) {


          if (isset($part[0]) && $part[0] === '{' && $part[strlen($part) - 1] === '}') {
            // If the current route part contains a parameter placeholder, extract the parameter name.
            $paramName = trim($part, '{}');
            // Add the extracted parameter to the list of parameters for the current route match.
            $params[$paramName] = $uriParts[$index];

            // Check if the current parameter contains a question mark
            if (str_contains($params[$paramName], '?')) {
              // Replace the first occurrence of the question mark with an empty string
              $params[$paramName] = strstr($params[$paramName], '?', true);
            }
          } elseif ($part !== $uriParts[$index]) {
            $match = false;
            break;
          }
        }

        // Return the matched route with any associated parameters.
        if ($match) {
          return [
            'method' => $route['method'],
            'controller' => $route['controller'],
            'action' => $route['action'],
            'params' => $params ?? null,
          ];
        }
      }
    }

    return null;
  }
}
