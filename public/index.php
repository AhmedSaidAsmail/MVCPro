<?php
require __DIR__ . '/../vendor/autoload.php';

use App\Router;
//$router      = new Router();
Router::addRoute([
    "pattern"    => "{name}/profile",
    "controller" => "Web\WebController",
    "action"     => "ahmedtest"
]);
Router::addRoute([
    "pattern"    => "{name}/son",
    "controller" => "Web\WebController",
    "action"     => "ahmedtest2"
]);
Router::addRoute([
    "pattern"    => "",
    "controller" => "Web\WebController",
    "action"     => "ahmedtest3"
]);
$router      = Router::$_instance;
$router->url = "";
$router->dispatch();
