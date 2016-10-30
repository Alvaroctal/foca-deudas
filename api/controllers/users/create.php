<?php

    $_POST = json_decode(file_get_contents('php://input'), true);

    if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        if (strlen($_POST['passwd']) >= 8) {
    
            $salt = md5(uniqid(rand(), true));
            $passwd = hash("sha256", $_POST['passwd'] . $salt);

            $stmt = $database->prepare('INSERT INTO users (username, name, surname, paypal, place, email, passwd, salt) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');

            $stmt->bind_param('ssssssss', $_POST['username'], $_POST['name'], $_POST['surname'], $_POST['paypal'], $_POST['place'], $_POST['email'], $passwd, $salt);

            if ($stmt->execute()) {
                $output['status'] = 1;
                $output['result'] = $stmt->insert_id;
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