<?php
return function ($application) {
    $shoutBoxTableName = $application->get('tableName');
    $request = $application->request();

    $user = $application->user();

    $memberID = $request->data->memberID;
    $memberName = $user->getName();
    $message = $request->data->message;

    $sth = $application->db()->prepare("INSERT INTO {$shoutBoxTableName} (
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

    return $application->db()->lastInsertId();
};
