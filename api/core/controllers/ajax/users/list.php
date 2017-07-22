<?php if (!$core->hasPermission('user.read')) return false;

$sentencia = $core->getDatabase()->prepare("SELECT u.id, u.username, u.email, u.rank, u.telegram, u.paypal, u.time, u.updateTime FROM users u ORDER BY u.id DESC");

if ($sentencia->execute()) {
    $output['status'] = 1;
    $output['data'] = $sentencia->fetchAll(PDO::FETCH_ASSOC);
} else {
    $output['code'] = 'no-sentence';
    if ($core->isDebug()) $output['debug'] = $sentencia->errorInfo();
}