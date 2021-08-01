<?php
require_once 'includes/Route.php';
require_once '../includes/classes/Jar.php';
$route = new Route(Route::GET);
$jars = Jar::collect(true);
$type = $route->body('type', false);
if ($type !== false) {
    if (isset($jars[$type])) {
        $route->success($jars[$type]);
    }
}
$route->success($jars);