<?php

    $_POST = json_decode(file_get_contents('php://input'), true);

    $stmt = $db->prepare('UPDATE users SET name = :name, username = :surname, paypal = :paypal, place = :place WHERE id = :id';

    $stmt->bindParam(':id', $_POST['id']);
    $stmt->bindParam(':name', $_POST['name']);
    $stmt->bindParam(':surname', $_POST['surname']);
    $stmt->bindParam(':paypal', $_POST['paypal']);
    $stmt->bindParam(':place', $_POST['place']);

    $stmt->execute();

    if ($stmt->rowCount() == 1) {
        $output['status'] = 1;
    } else {
        $output['code'] = 'no-user-update';
        $output['return'] = 'An unexpected error ocurred';
    }

?>