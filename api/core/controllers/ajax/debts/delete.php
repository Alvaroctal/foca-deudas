<?php if (!$core->hasPermission('debt.delete')) return false;
    
$sentencia = $core->getDatabase()->prepare("DELETE FROM debts WHERE id = :id");

$sentencia->bindParam(':id', $_POST['id']);

if ($sentencia->execute()) {
    if ($sentencia->rowCount() == 1) {
        $output['status'] = 1;
        $core->log('users', 'Deleted debt ('.$_POST['id'].')');
            
    } else {
        $output['code'] = 'no-debt';
        $output['return'] = 'Unable to find debt';
    }
} else {
    $output['code'] = 'no-delete';
    $output['return'] = 'An unexpected error ocurred';
    if ($core->isDebug()) $output['debug'] = $sentencia->errorInfo();
}