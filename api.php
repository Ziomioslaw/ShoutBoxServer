<?php
require_once('../../SSI.php');
require_once('vendor/autoload.php');

use flight\Engine;

$dbConnection = require_once('db.connection.php');
$shoutBoxTableName = $dbConnection['dbPrefix'] . 'shoutbox';
$application = new Engine();

$application->register('db', 'PDO', array($dbConnection['dbtype'], $dbConnection['dbUser'], $dbConnection['dbPassword']), function($db) {
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
});

$application->route('GET /shout', function($id) use($application, $shoutBoxTableName) {
    $db->prepare("SELECT * FROM `{$shoutBoxTableName}` ORDER BY `ID_SHOUT` DESC LIMIT 20");
    $sth->execute();

    return $application->_json($sth->fetchAll(PDO::FETCH_ASSOC));
));
