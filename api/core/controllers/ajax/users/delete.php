<?php if (!$core->hasPermission('user.delete')) return false;
    
$sentencia = $core->getDatabase()->prepare("DELETE FROM users WHERE id = :id");

$sentencia->bindParam(':id', $_POST['id']);

if ($sentencia->execute()) {
    if ($sentencia->rowCount() == 1) {
        $output['status'] = 1;
        $core->log('users', 'Deleted user ('.$_POST['id'].')');
            
    } else {
        $output['code'] = 'no-user';
        $output['return'] = 'Unable to find user';
    }
} else {
    $output['code'] = 'no-delete';
    $output['return'] = 'An unexpected error ocurred';
    if ($core->isDebug()) $output['debug'] = $sentencia->errorInfo();
}