<?php

namespace r3pt1s\BanSystem\command\notify;

use pocketmine\plugin\PluginOwned;
use r3pt1s\BanSystem\BanSystem;
use r3pt1s\BanSystem\manager\notify\NotifyManager;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\lang\Translatable;
use pocketmine\player\Player;

class NotifyCommand extends Command implements PluginOwned {

    public function __construct(string $name, Translatable|string $description = "", Translatable|string|null $usageMessage = null, array $aliases = []) {
        parent::__construct($name, $description, $usageMessage, $aliases);
        $this->setPermission("bansystem.notify.command");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool {
        if ($sender instanceof Player) {
            if ($sender->hasPermission($this->getPermission())) {
                if (NotifyManager::getInstance()->receiveNotify($sender)) {
                    $sender->sendMessage(BanSystem::getPrefix() . "§7Você não receberá mais notificações!");
                    NotifyManager::getInstance()->setNotify($sender, false);
                } else {
                    $sender->sendMessage(BanSystem::getPrefix() . "§7Você receberá notificações!");
                    NotifyManager::getInstance()->setNotify($sender, true);
                }
            } else {
                $sender->sendMessage(BanSystem::getPrefix() . BanSystem::NO_PERMS);
            }
        }
        return true;
    }

    public function getOwningPlugin(): BanSystem {
        return BanSystem::getInstance();
    }
}