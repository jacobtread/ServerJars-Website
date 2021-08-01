<?php
require_once 'includes/Route.php';
require_once '../includes/classes/Stats.php';
require_once '../includes/classes/Jar.php';
$route = new Route(Route::GET, ['accessKey']);
if ($route->isAccepted()) {
    $accessKey = $route->body('accessKey');
    if ($accessKey === Route::ADMIN_KEY) {
        Jar::collect(false, true);
        Stats::updateTime();
        die('Cache Invalidated');
    }
}

http_response_code(403);