<?php
    if (isset($_SESSION['user'])) {
        $output['result'] = $_SESSION;
        $output['status'] = 1;
    } else {
        $output['code'] = 'no-login';
        $output['return'] = 'You must be logged in';
    }
?>