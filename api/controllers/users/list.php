<?php
    
    $result = $database->query("SELECT id, username, name, surname, paypal, telegram, place, email FROM users");

    $output['status'] = 1;
    $output['result'] = $result->fetch_all(MYSQLI_ASSOC);
?>