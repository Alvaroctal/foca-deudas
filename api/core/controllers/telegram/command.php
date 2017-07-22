<?php $GLOBALS['core'] = $core;
$telegram->addCommandsPath($core->getCorePath().'/controllers/telegram/commands');
$telegram->enableLimiter();
$telegram->handle();