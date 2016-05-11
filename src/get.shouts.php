<?php
define('DEFAULT_SHOUTS_LIMIT', 20);
define('ALLOW_TO_DELETE_TIME', 18000);

return function ($application) {
    $user = $application->user();
    $shoutBoxTableName = $application->get('tableName');
    $limit = DEFAULT_SHOUTS_LIMIT;

    $delete = $user->isAdmin()
        ? '1'
        : 'CASE WHEN (`ID_MEMBER` = ' . $user->getId() . ' AND `time` > ' . ($application->time() - ALLOW_TO_DELETE_TIME) . ') THEN 1 ELSE 0 END';

    $sth = $application->db()->prepare("SELECT *
        FROM (SELECT
                `ID_SHOUT` AS id,
                `displayname` AS member_name,
                `ID_MEMBER` AS member_id,
                $delete AS can_delete,
                `time` AS time,
                `message`
            FROM $shoutBoxTableName
            ORDER BY `ID_SHOUT` DESC
            LIMIT $limit
        ) AS s
        ORDER BY s.id");

    $sth->execute();

    return $sth->fetchAll(PDO::FETCH_ASSOC);
};
