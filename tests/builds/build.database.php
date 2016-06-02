<?php
define('DEFAULT_LAST_SHOUT', 1);
define('DEFAULT_TIMER_START', 90000);
$userList = require_once('user.list.php');
$lastShoutId = DEFAULT_LAST_SHOUT;
$timer = DEFAULT_TIMER_START;

function buildDB($application) {
    global $lastShoutId, $timer;

    $application->register('db', 'PDO', array('sqlite::memory:'));
    $application->set('tableName', 'shoutboxTable');
    $application->db()->exec("CREATE TABLE IF NOT EXISTS `{$application->get('tableName')}`
        (
            `ID_SHOUT` integer PRIMARY KEY AUTOINCREMENT,
            `ID_MEMBER` int(11) NOT NULL,
            `displayname` tinytext,
            `message` text NOT NULL,
            `email` tinytext,
            `time` int(11) NOT NULL,
            `edited` int(11)
        )");

    $lastShoutId = DEFAULT_LAST_SHOUT;
    $timer = DEFAULT_TIMER_START;
}

function setMainTimer($time) {
    global $timer;

    $timer = $time;
}

function insertIntoShoutbox($application, $memberId, array $values = array()) {
    global $lastShoutId, $timer, $userList;

    $std = $application->db()->prepare("INSERT INTO `{$application->get('tableName')}` (`ID_SHOUT`, `ID_MEMBER`, `displayname`, `message`, `email`, `time`) VALUES (?, ?, ?, ?, ?, ?)");

    $values['id'] = $lastShoutId++;

    if (!array_key_exists('message', $values)) {
        $values['message'] = 'test message';
    }

    $user = $userList[$memberId];
    $values['memberName'] = $user['name'];
    $values['email'] = $user['email'];

    if (!array_key_exists('time', $values)) {
        $values['time'] = $timer;
        $timer += 2;
    } else {
        $timer = $values['time'] + 2;
    }

    $std->execute(array(
            $values['id'],
            $memberId,
            $values['memberName'],
            $values['message'],
            $values['email'],
            $values['time']
        ));
}
