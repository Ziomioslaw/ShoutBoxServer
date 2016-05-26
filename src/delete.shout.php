<?php
return function ($application, $arguments) {
    $shoutId = $arguments[0];
    $shoutBoxTableName = $application->get('tableName');
    $user = $application->user();

    $delete = $application->buildDeleteSQL($user);

    $stm = $application->db()->prepare("DELETE FROM {$shoutBoxTableName} WHERE ID_SHOUT = :shoutId AND {$delete}");

    $stm->bindParam(':shoutId', $shoutId, PDO::PARAM_INT);

    return $stm->execute();
};
