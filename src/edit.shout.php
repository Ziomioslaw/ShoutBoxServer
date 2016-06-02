<?php
return function ($application, $arguments) {
    $shoutId = $arguments[0];
    $shoutBoxTableName = $application->get('tableName');
    $request = $application->request();
    $user = $application->user();
    $search = $request->data->search;
    $replace = $request->data->replace;
    $delete = $application->buildDeleteSQL($user);
    $time = $application->time();

    $stm = $application->db()->prepare("UPDATE {$shoutBoxTableName}
            SET `message` = replace(`message`, :search, :replace),
            `edited` = :time
            WHERE ID_SHOUT = :shoutId AND {$delete}");

    $stm->bindParam(':search', $search, PDO::PARAM_STR);
    $stm->bindParam(':replace', $replace, PDO::PARAM_STR);
    $stm->bindParam(':shoutId', $shoutId, PDO::PARAM_INT);
    $stm->bindParam(':time', $time, PDO::PARAM_INT);

    return $stm->execute();
};
