<?php

if ($core->getDatabase()->query("SELECT u.id FROM users u WHERE u.id = {$session['id']}", PDO::FETCH_ASSOC)->fetch()) {
    $output['status'] = 1;
    $output['data'] = $session;
} else $output['code'] = 'no-session';
