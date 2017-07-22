<?php

namespace Longman\TelegramBot\Commands\UserCommands;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Request;
use PDO;

class TopCommand extends UserCommand {
    /**
     * @var string
     */
    protected $name = 'top';
    /**
     * @var string
     */
    protected $description = 'Top morosos';
    /**
     * @var string
     */
    protected $usage = '/top';
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

        $data['text'] = '🔷  TOP MOROSOS  🔷'.PHP_EOL;

        foreach ($GLOBALS['core']->getDatabase()->query("SELECT u.id, u.username, u.telegram_username, count(DISTINCT ud.debt) as 'debts', coalesce(sum(ud.quantity), 0) as 'total' FROM users u LEFT JOIN user_debt ud ON ud.user = u.id ANd ud.state IN ('pending','rejected') GROUP BY u.id ORDER BY total DESC", PDO::FETCH_ASSOC) as $row) {
            $data['text'] .= PHP_EOL.($row['debts'] == 0 ? '🔹' : '🔸').(empty($row['telegram_username']) ? $row['username'] : '@'.$row['telegram_username']).': '.($row['debts'] == 0 ? 'Sin deudas' : $row['debts'].' deudas ('.$row['total'].'€)');
        }
        
        return Request::sendMessage($data);
    }
}