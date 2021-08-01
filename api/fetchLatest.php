<?php
require_once 'includes/Route.php';
require_once '../includes/classes/Jar.php';
$route = new Route(Route::GET, ['type']);
if ($route->isAccepted()) {
    $type = strtolower($route->body('type'));
    if (strpos($type, '../') > -1) {
        $route->error('Exploit Attempted', 'Warning! you do not attempt to serve files outside of the jars dir.');
    }
    $jars = Jar::collect();
    $mainCategory = null;
    $childCategory = null;
    $tree = Jar::getTree($type, $jars, false);
    if ($tree !== false) {
        $mainCategory = $tree;
        $file = '../jars/' . $mainCategory . '/' . $type . '/';
        $fileType = $type === 'pocketmine' ? '.phar' : '.jar';
        $exists = false;
        $categoryJars = $jars[$mainCategory][$type];
        if (sizeof($categoryJars) > 0) {
            $latest = $categoryJars[0];
            $file .= $latest['file'];
            if (file_exists($file)) {
                $route->success($latest);
            } else {
                $route->error('Missing Jar', 'Hmm the latest version was missing please wait for the cache to recalculate. (Every 10min\'s)', 410);
            }
        } else {
            $route->error('Missing Jar\'s', 'The type "' . $type . '" contains no jars try another?', 404);
        }
    } else {
        $route->error('Unknown Type', 'The type "' . $type . '" was not found');
    }

} else {
    $route->error('Missing Type', 'You must provide a type of Jar', 400);
}