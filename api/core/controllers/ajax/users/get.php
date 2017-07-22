<?php if (!$core->hasPermission('user.read')) return false;

$sentencia = $core->getDatabase()->prepare("SELECT u.id, u.username, u.email, u.rank, u.telegram, u.paypal, u.time, u.updateTime FROM users u WHERE u.id = :id ORDER BY u.id DESC");

$sentencia->bindValue(':id', $match['params']['id']);

if ($sentencia->execute()) {
    if ($row = $sentencia->fetch(PDO::FETCH_ASSOC)) {
        $output['status'] = 1;
        if ($session['id'] == $row['id']) $row['telegram_token'] = rtrim(base64_encode(openssl_encrypt((empty($row['updateTime']) ? $row['time'] : $row['updateTime']), 'AES-256-CBC', $environment['jwt']['key'], null, $environment['telegram']['iv'])), '=');
        $output['data'] = $row;
    } else $output['code'] = 'no-user';
} else {
    $output['code'] = 'no-sentence';
    if ($core->isDebug()) $output['debug'] = $sentencia->errorInfo();
}