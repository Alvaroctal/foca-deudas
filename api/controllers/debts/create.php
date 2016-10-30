<?php

    $_POST = json_decode(file_get_contents('php://input'), true);

    $stmt = $database->prepare('INSERT INTO debts (owner, title, description, amount) VALUES (?, ?, ?, ?)');

    $stmt->bind_param('issd', $_SESSION['user'], $_POST['title'], $_POST['description'], $_POST['amount']);

    if ($stmt->execute()) {
        $output['status'] = 1;
        $output['result'] = $stmt->insert_id;
    } else {
        $output['code'] = 'no-create-debt';
        $output['return'] = 'An unexpected error ocurred';
    }

?>