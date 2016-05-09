<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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
$shoutBoxTableName = $dbConnection['dbPrefix'] . 'shoutbox';
$deleteTime = 18000; // 5 minutes

$application = new Engine();
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

$application->route('GET /shouts', function() use($application, $shoutBoxTableName, $deleteTime) {
    $function = require_once('get.shouts.php');

    return $application->json($function(
            $application,
            $shoutBoxTableName,
            $deleteTime));
});

$application->route('POST /shout', function() use($application, $shoutBoxTableName) {
    $function = require_once('post.shout.php');

    return $application->json($function(
            $application,
            $shoutBoxTableName
        ));
});

$application->map('error', function(Exception $ex) use($application) {
    $application->_error($ex);
});

$application->start();
