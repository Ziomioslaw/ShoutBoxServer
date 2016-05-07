<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('../../SSI.php');
if (!defined('SMF')) {
    die('Hacking attempt...');
}

require_once('vendor/autoload.php');

use flight\Engine;

$dbConnection = require_once('db.connection.php');
$shoutBoxTableName = $dbConnection['dbPrefix'] . 'shoutbox';

$application = new Engine();
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

$application->route('GET /shout', function() use($application, $shoutBoxTableName) {
    $sth = $application->db()->prepare("SELECT
            `ID_SHOUT` AS id,
            `displayname` AS member_name,
            `ID_MEMBER` AS member_id,
            '0' AS can_delete,
            `time` AS time,
            `message`
        FROM `{$shoutBoxTableName}`
        ORDER BY `ID_SHOUT` DESC
        LIMIT 20");

    $sth->execute();

    return $application->_json($sth->fetchAll(PDO::FETCH_ASSOC));
});

$application->route('POST /shout', function() use($application, $shoutBoxTableName) {
    $request = $application->request();
    $memberID = $request->data->memberID;
    $memberName = $request->data->memberName;
    $message = $request->data->message;

    $sth = $this->pdo->prepare("INSERT INTO {$shoutBoxTableName} (
            `ID_MEMBER`,
            `displayname`,
            `message`,
            `time`
        ) VALUES (
            :memberID,
            :memberName,
            :message,
            UNIX_TIMESTAMP()
        )");

    $sth->bindParam(':memberID', $memberID, PDO::PARAM_INT);
    $sth->bindParam(':memberName', $memberName, PDO::PARAM_STR);
    $sth->bindParam(':message', $message, PDO::PARAM_STR);
    $sth->execute();

    return $this->pdo->lastInsertId();
});

$application->map('error', function(Exception $ex) use($application) {
    $application->_error($ex);
});

$application->start();
