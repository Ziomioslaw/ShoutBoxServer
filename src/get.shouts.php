<?php
define('DEFAULT_SHOUTS_LIMIT', 20);

return function ($application, $shoutBoxTableName, $deleteTime) {
    $user = $application->user();

    $delete = $user->isAdmin()
        ? '1'
        : 'IF(time > ' . (time() - $deleteTime) . ', 1, 0)';

    $sth = $application->db()->prepare("SELECT
            `ID_SHOUT` AS id,
            `displayname` AS member_name,
            `ID_MEMBER` AS member_id,
            $delete AS can_delete,
            `time` AS time,
            `message`
        FROM `{$shoutBoxTableName}`
        ORDER BY `ID_SHOUT` DESC
        LIMIT " . DEFAULT_SHOUTS_LIMIT);

    $sth->execute();

    return $sth->fetchAll(PDO::FETCH_ASSOC);
};
