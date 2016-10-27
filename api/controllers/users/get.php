<?php

    $id = (int) $match['params']['id'];
    
    $result = $database->query('SELECT id, username, name, surname, paypal, telegram, place, email FROM users WHERE id = $id');

    $output['status'] = 1;
    $output['result'] = $result->fetch_array(MYSQLI_ASSOC);
?>