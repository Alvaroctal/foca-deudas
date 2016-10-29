<?php

    $statement = $database->prepare('SELECT id, username, name, surname, paypal, telegram, place, email FROM users WHERE id = ?');

    $statement->bind_param('i', $match['params']['id']);
    $statement->execute();

    $output['status'] = 1;
    $output['result'] = $statement->get_result()->fetch_assoc();
?>