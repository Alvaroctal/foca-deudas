<?php

    $_POST = json_decode(file_get_contents('php://input'), true);

    if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        if (strlen($_POST['passwd']) => 8) {
    
            $salt = md5(uniqid(rand(), true));
            $passwd = hash("sha256", $_POST['passwd'] . $salt);

            $stmt = $db->prepare('INSERT INTO users (username, name, surname, rank, paypal, place, passwd, salt) VALUES (:username, :name, :surname, :rank, :paypal, :place, :passwd, :salt');

            $stmt->bindParam(':usename', $_POST['usename']);
            $stmt->bindParam(':name', $_POST['name']);
            $stmt->bindParam(':surname', $_POST['surname']);
            $stmt->bindParam(':rank', $_POST['rank']);
            $stmt->bindParam(':paypal', $_POST['paypal']);
            $stmt->bindParam(':place', $_POST['place']);
            $stmt->bindParam(':passwd', $_POST['passwd']);
            $stmt->bindParam(':salt', $_POST['salt']);

            $stmt->execute();

            if ($stmt->rowCount() == 1) {
                $output['status'] = 1;
                $output['result'] = lastInsertId();
            } else {
                $output['code'] = 'no-register';
                $output['return'] = 'An unexpected error ocurred';
            }
        } else {
            $output['code'] = 'no-register-passwd';
            $output['return'] = 'Password is not long enough';
        }
    } else {
        $output['code'] = 'no-register-email';
        $output['return'] = 'Email field is not a valid email';
    }

?>