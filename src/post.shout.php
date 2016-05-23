<?php
return function ($application) {
    $shoutBoxTableName = $application->get('tableName');
    $request = $application->request();
    $user = $application->user();

    $memberID = $user->getId();
    $memberName = $user->getName();
    $message = $request->data->message;
    $time = $application->time();

    $sth = $application->db()->prepare("INSERT INTO {$shoutBoxTableName} (
            `ID_MEMBER`,
            `displayname`,
            `message`,
            `time`
        ) VALUES (
            :memberID,
            :memberName,
            :message,
            :unixTimeStamp
        )");

    $sth->bindParam(':memberID', $memberID, PDO::PARAM_INT);
    $sth->bindParam(':memberName', $memberName, PDO::PARAM_STR);
    $sth->bindParam(':message', $message, PDO::PARAM_STR);
    $sth->bindParam(':unixTimeStamp', $time, PDO::PARAM_INT);

    $sth->execute();

    return $application->db()->lastInsertId();
};
