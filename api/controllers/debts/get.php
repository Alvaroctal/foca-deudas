<?php

    $statement = $database->prepare('SELECT d.id, d.owner, d.title, d.description, d.amount, d.time , u.username, u.name, u.surname, u.paypal, u.telegram FROM debts d JOIN users u ON d.owner = u.id WHERE d.id = ?');

    $statement->bind_param('i', $match['params']['id']);
    $statement->execute();

    $output['status'] = 1;
    $output['result'] = $statement->get_result()->fetch_assoc();
?>