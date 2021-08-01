<?php
require_once 'includes/Route.php';
require_once '../includes/classes/Jar.php';
require_once '../includes/classes/Stats.php';
$route = new Route(Route::GET, ['type']);
if ($route->isAccepted()) {
    $type = strtolower($route->body('type'));
    $version = $route->body('version');
    $isLatest = $version === null;
    if (strpos($type, '../') > -1 || (!$isLatest && strpos($version, '../') > -1)) {
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
        if ($isLatest) {
            $categoryJars = $jars[$mainCategory][$type];
            if (sizeof($categoryJars) > 0) {
                $latest = $categoryJars[0];
                $file .= $latest['file'];
                if (file_exists($file)) {
                    $exists = true;
                } else {
                    $route->error('Missing Jar', 'Hmm the latest version was missing please wait for the cache to recalculate. (Every 10min\'s)', 410);
                }
            } else {
                $route->error('Missing Jar\'s', 'The type "' . $type . '" contains no jars try another?', 404);
            }
        } else {
            if (strpos($version, 'pre') > 0) {
                $version = str_replace('pre', '-pre', $version);
            }
            $file .= $type . '-' . $version . $fileType;
            if (file_exists($file)) {
                $exists = true;
            } else {
                $route->error('Missing Jar', 'That version didn\'t seem to exist try another or leave blank for latest', 404);
            }
        }
        if ($exists) {
            ob_start();
            Stats::incDownloads();
            header('Location: ' . substr($file, 2));
            ob_clean();
            die();
        }
    } else {
        $route->error('Unknown Type', 'The type "' . $type . '" was not found');
    }

} else {
    $route->error('Missing Type', 'You must provide a type of Jar', 400);
}