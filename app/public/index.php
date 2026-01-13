<?php
session_start();
/**
 * This is the central route handler of the application.
 * It uses FastRoute to map URLs to controller methods.
 *
 * See the documentation for FastRoute for more information: https://github.com/nikic/FastRoute
 */

require __DIR__ . '/../vendor/autoload.php';

use FastRoute\RouteCollector;
use function FastRoute\simpleDispatcher;

/**
 * Define the routes for the application.
 */
$dispatcher = simpleDispatcher(function (RouteCollector $r) {
    // Authentication
    $r->addRoute('GET',  '/login',    ['App\Controllers\AuthenticationController', 'showLogin']);
    $r->addRoute('POST', '/login',    ['App\Controllers\AuthenticationController', 'login']);
    $r->addRoute('POST', '/logout',   ['App\Controllers\AuthenticationController', 'logout']);

    // Registration
    $r->addRoute('GET',  '/register', ['App\Controllers\AuthenticationController', 'showRegister']);
    $r->addRoute('POST', '/register', ['App\Controllers\AuthenticationController', 'register']);

    //Salons
    // Archive
    $r->addRoute( 'GET', '/salons', ['App\Controllers\SalonController', 'index']);

    // Detail
    $r->addRoute('GET', '/salons/{id:\d+}', ['App\Controllers\SalonController', 'showOneSalon']);

    // Create form + store
    $r->addRoute('GET',  '/salons/create', ['App\Controllers\SalonController', 'create']);
    $r->addRoute('POST', '/salons/create', ['App\Controllers\SalonController', 'addNewSalon']);

    // Delete
    $r->addRoute('POST', '/salons/{id:\d+}/delete', ['App\Controllers\SalonController', 'delete']);

    // Edit form + update
    $r->addRoute('GET',  '/salons/{id:\d+}/edit', ['App\Controllers\SalonController', 'edit']);
    $r->addRoute('POST', '/salons/{id:\d+}/edit', ['App\Controllers\SalonController', 'update']);

    //SalonServices
    $r->addRoute('GET',  '/salons/{salonId:\d+}/services',                   ['App\Controllers\SalonServicesController', 'index']);
    $r->addRoute('GET',  '/salons/{salonId:\d+}/services/create',            ['App\Controllers\SalonServicesController', 'create']);
    $r->addRoute('POST', '/salons/{salonId:\d+}/services/create',            ['App\Controllers\SalonServicesController', 'store']);

    $r->addRoute('GET',  '/salons/{salonId:\d+}/services/{id:\d+}',          ['App\Controllers\SalonServicesController', 'show']);

    $r->addRoute('GET',  '/salons/{salonId:\d+}/services/{id:\d+}/edit',     ['App\Controllers\SalonServicesController', 'edit']);
    $r->addRoute('POST', '/salons/{salonId:\d+}/services/{id:\d+}/edit',     ['App\Controllers\SalonServicesController', 'update']);

    $r->addRoute('POST', '/salons/{salonId:\d+}/services/{id:\d+}/delete',   ['App\Controllers\SalonServicesController', 'delete']);


    //Users
    // Archive per role
    $r->addRoute('GET',  '/users/{role}',                 ['App\Controllers\UsersController', 'index']);

    // Create form + store
    $r->addRoute('GET',  '/users/{role}/create',          ['App\Controllers\UsersController', 'create']);
    $r->addRoute('POST', '/users/{role}/create',          ['App\Controllers\UsersController', 'store']);

    // Detail
    $r->addRoute('GET',  '/users/{role}/{id:\d+}',        ['App\Controllers\UsersController', 'show']);

    // Edit form + update
    $r->addRoute('GET',  '/users/{role}/{id:\d+}/edit',   ['App\Controllers\UsersController', 'edit']);
    $r->addRoute('POST', '/users/{role}/{id:\d+}/edit',   ['App\Controllers\UsersController', 'update']);

    // Delete
    $r->addRoute('POST', '/users/{role}/{id:\d+}/delete', ['App\Controllers\UsersController', 'delete']);

    // Appointments
    // Archive
    $r->addRoute('GET',  '/appointments',                 ['App\Controllers\AppointmentsController', 'index']);

    // Create form + store
    // Customer booking flow
    $r->addRoute('GET',  '/salons/{salonId:\d+}/book',                 ['App\Controllers\AppointmentsController', 'bookChooseService']);
    $r->addRoute('GET',  '/salons/{salonId:\d+}/book/date',            ['App\Controllers\AppointmentsController', 'bookChooseDate']);
    $r->addRoute('GET',  '/salons/{salonId:\d+}/book/slots',           ['App\Controllers\AppointmentsController', 'bookChooseSlot']);
    $r->addRoute('POST', '/salons/{salonId:\d+}/book/confirm',         ['App\Controllers\AppointmentsController', 'bookConfirm']);

    $r->addRoute('GET',  '/appointments/create',          ['App\Controllers\AppointmentsController', 'create']);
    $r->addRoute('POST', '/appointments/create',          ['App\Controllers\AppointmentsController', 'store']);

    // Detail
    $r->addRoute('GET',  '/appointments/{id:\d+}',        ['App\Controllers\AppointmentsController', 'show']);

    // Edit form + update
    $r->addRoute('GET',  '/appointments/{id:\d+}/edit',   ['App\Controllers\AppointmentsController', 'edit']);
    $r->addRoute('POST', '/appointments/{id:\d+}/edit',   ['App\Controllers\AppointmentsController', 'update']);

    // Delete
    $r->addRoute('POST', '/appointments/{id:\d+}/delete', ['App\Controllers\AppointmentsController', 'delete']);


});

/**
 * Get the request method and URI from the server variables and invoke the dispatcher.
 */
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = strtok($_SERVER['REQUEST_URI'], '?');
$routeInfo = $dispatcher->dispatch($httpMethod, $uri);

/**
 * Switch on the dispatcher result and call the appropriate controller method if found.
 */
switch ($routeInfo[0]) {
    // Handle not found routes
    case FastRoute\Dispatcher::NOT_FOUND:
        http_response_code(404);
        echo 'Not Found';
        break;
    // Handle routes that were invoked with the wrong HTTP method
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        http_response_code(405);
        echo 'Method Not Allowed';
        break;

    /**
     * $routeInfo contains the data about the matched route.
     *
     * $routeInfo[1] is the whatever we define as the third argument the `$r->addRoute` method.
     *  For instance for: `$r->addRoute('GET', '/hello/{name}', ['App\Controllers\HelloController', 'greet']);`
     *  $routeInfo[1] will be `['App\Controllers\HelloController', 'greet']`
     *
     * We can use class strings like `App\Controllers\HelloController` to create new instances of that class.
     * In PHP, we can use a string to call a class method dynamically, like this: `$instance->$methodName($args);`
     */

    //invoke the controller and method using the data in $routeInfo[1]
    case FastRoute\Dispatcher::FOUND:
        $class = $routeInfo[1][0];
        $method = $routeInfo[1][1];
        $controller = new $class();
        $vars = $routeInfo[2];
        $controller->$method(...array_values($vars));
        break;

}
