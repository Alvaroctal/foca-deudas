<?php

namespace Longman\TelegramBot\Commands\UserCommands;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Request;

class LinkCommand extends UserCommand {
    /**
     * @var string
     */
    protected $name = 'link';
    /**
     * @var string
     */
    protected $description = 'Une tu cuenta de telegram con FocaDeudas';
    /**
     * @var string
     */
    protected $usage = '/link <code>';
    /**
     * @var string
     */
    protected $version = '1.0.0';
    /**
     * Command execute method
     *
     * @return \Longman\TelegramBot\Entities\ServerResponse
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function execute() {

        $message = $this->getMessage();
        $data = ['chat_id' => $message->getChat()->getId()];

        Request::sendChatAction(['chat_id' => $message->getChat()->getId(), 'action'  => 'typing']);

        if (!empty($code = trim($message->getText(true)))) {

            $environment = $GLOBALS['core']->getEnvironment();
            $updateTime = openssl_decrypt(base64_decode($code), 'AES-256-CBC', $environment['jwt']['key'], null, $environment['telegram']['iv']);

            if (preg_match("/^(\d{4})-(\d{2})-(\d{2}) ([01][0-9]|2[0-3]):([0-5][0-9]):([0-5][0-9])$/", $updateTime, $matches) && checkdate($matches[2], $matches[3], $matches[1])) { 

                $sentencia = $GLOBALS['core']->getDatabase()->prepare("UPDATE users u SET u.telegram = :telegram, u.telegram_username = :telegram_username, updateTime = NOW() WHERE (u.updateTime IS NULL AND u.time = :updateTime) OR (u.updateTime IS NOT NULL AND u.updateTime = :updateTime)");
                $sentencia->bindParam(':telegram', $message->getFrom()->getId());
                $sentencia->bindParam(':telegram_username', $message->getFrom()->getUsername());
                $sentencia->bindParam(':updateTime', $updateTime);

                if ($sentencia->execute() && $sentencia->rowCount() == 1) $data['text'] = 'Cuenta vinculada';
                else $data['text'] = 'El código introducido ya no es válido';
            } else $data['text'] = 'El código introducido no es válido';
        } else $data['text'] = 'Uso: '.$this->usage;
        
        return Request::sendMessage($data);
    }
}