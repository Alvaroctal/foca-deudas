<?php

    $output = array('status' => 1, 'code' => $_GET['action']);

    // Allow only ajax request

    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    
        if ($_GET['action'] == 'get-user') {
            include_once __DIR__ . '/../models/user.php';

            $user = new User(['id' => $_GET['id']]);

            $output['data'] = $user->export();
        } else {
            $output = array('status' => 0, 'code' => 'no-action', 'data' => 'Unable to find action');
        }
    } else {
        $output = array('status' => 0, 'code' => 'no-ajax', 'data' => 'Only AJAX request are allowed');
    }

    echo json_encode($output);

    die();
?>