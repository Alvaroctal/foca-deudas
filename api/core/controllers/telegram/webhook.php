<?php

$result = $telegram->setWebhook('https://'.$_SERVER['SERVER_NAME'].$core->getRelativePath().'/telegram/'.$environment['telegram']['webhook'].'/command');
if ($result->isOk()) $output['status'] = 1;