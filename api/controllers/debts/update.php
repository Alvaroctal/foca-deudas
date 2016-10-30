<?php

    $_POST = json_decode(file_get_contents('php://input'), true);

    $stmt = $database->prepare('UPDATE debts SET description = ? WHERE id = ? AND owner = ?');

    $stmt->bind_param('sii', $_POST['description'], $_POST['id'], $_SESSION['user']);

    $stmt->execute();

    if ($stmt->affected_rows == 1) {
        $output['status'] = 1;
    } else {
        $output['code'] = 'no-user-update';
        $output['return'] = 'An unexpected error ocurred';
    }

?>