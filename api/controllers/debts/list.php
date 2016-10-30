<?php
    
    $result = $database->query('SELECT d.id, d.title, d.amount, d.time, u.username FROM debts d JOIN users u ON d.owner = u.id');

    $output['status'] = 1;
    $output['result'] = $result->fetch_all(MYSQLI_ASSOC);
?>