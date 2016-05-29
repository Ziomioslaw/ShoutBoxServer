<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
define('ALLOW_TO_DELETE_TIME', 18000);

require_once('../../SSI.php');
if (!defined('SMF')) {
    die('Hacking attempt...');
}

if (!$context['user']['is_logged']) {
    die('Please login');
}

require_once('vendor/autoload.php');
require_once('User.php');

use flight\Engine;

$dbConnection = require_once('db.connection.php');

$application = new Engine();
$application->set('tableName', $dbConnection['dbPrefix'] . 'shoutbox');
$application->register('user', 'User', array($context));
$application->register(
    'db',
    'PDO',
    array(
        $dbConnection['dbType'],
        $dbConnection['dbUser'],
        $dbConnection['dbPassword']
    ),
    function($db) {
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
);

$application->map('buildDeleteSQL', function(User $user) use($application) {
    return $user->isAdmin()
        ? '1'
        : '`ID_MEMBER` = ' . $user->getId() . ' AND `time` > ' . ($application->time() - ALLOW_TO_DELETE_TIME);
});

$application->map('time', function() {
    return time();
});

$application->route('GET /shouts', function() use($application) {
    require_once(__DIR__ . '/../../Sources/Subs.php');

    $function = require_once('get.shouts.php');
    $results = $function($application);

    foreach($results as &$row) {
        $row['time'] = timeformat($row['time']);
        $row['message'] = doUBBC(censorText($row['message']));
    }

    return $application->json($results);
});

$application->route('POST /shout', function() use($application) {
    return make($application, 'post.shout.php');
});

$application->route('POST /shout/@id:[0-9]+/delete', function($id) use($application) {
    return make($application, 'delete.shout.php', func_get_args());
});

$application->route('POST /shout/@id:[0-9]+/edit', function($id) use($application) {
    return make($application, 'edit.shout.php', func_get_args());
});

$application->map('error', function(Exception $ex) use($application) {
    $application->_error($ex);
});

$application->start();

function make($application, $fileName, $arguments = array()) {
    $function = require_once($fileName);

    return $application->json($function($application, $arguments));
}
