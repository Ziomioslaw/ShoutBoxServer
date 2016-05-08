<?php
return function ($application, $shoutBoxTableName) {
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
};
