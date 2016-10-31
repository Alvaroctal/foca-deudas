<?php

    $_POST = json_decode(file_get_contents('php://input'), true);

    $stmt = $database->prepare('UPDATE users SET name = ?, surname = ?, paypal = ?, place = ? WHERE id = ?');

    $stmt->bind_param('ssssi', $_POST['name'], $_POST['surname'], $_POST['paypal'], $_POST['place'], $_POST['id']);

    $stmt->execute();

    if ($stmt->affected_rows == 1) {
        $output['status'] = 1;
    } else {
        $output['code'] = 'no-user-update';
        $output['return'] = 'An unexpected error ocurred';
    }

?>