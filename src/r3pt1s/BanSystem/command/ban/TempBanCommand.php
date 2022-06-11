<?php

namespace r3pt1s\BanSystem\command\ban;

use pocketmine\plugin\PluginOwned;
use r3pt1s\BanSystem\BanSystem;
use r3pt1s\BanSystem\manager\ban\BanManager;
use r3pt1s\BanSystem\provider\CurrentProvider;
use r3pt1s\BanSystem\utils\Utils;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\lang\Translatable;

class TempBanCommand extends Command implements PluginOwned {

    public function __construct(string $name, Translatable|string $description = "", Translatable|string|null $usageMessage = null, array $aliases = []) {
        parent::__construct($name, $description, $usageMessage, $aliases);
        $this->setPermission("bansystem.tempban.command");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool {
        if ($sender->hasPermission($this->getPermission())) {
            if (isset($args[0]) && isset($args[1]) && isset($args[2])) {
                if (CurrentProvider::get()->isPlayerCreated($args[0])) {
                    if (!BanManager::getInstance()->isBanned($args[0])) {
                        if (Utils::convertStringToDateFormat($args[2]) === null) {
                            $sender->sendMessage(BanSystem::getPrefix() . "§cFavor colocar um banimento valido! Exemlpo: /tempban DK9695 hack 1d");
                        } else {
                            $sender->sendMessage(BanSystem::getPrefix() . "§7O jogador §e" . $args[0] . " §7foi banido!");
                            BanManager::getInstance()->tempBanPlayer($args[0], $sender->getName(), $args[1], $args[2]);
                        }
                    } else {
                        $sender->sendMessage(BanSystem::getPrefix() . "§7O jogador §e" . $args[0] . " §7já foi banido!");
                    }
                } else {
                    $sender->sendMessage(BanSystem::getPrefix() . "§7O jogador não existe!");
                }
            } else {
                $sender->sendMessage(BanSystem::getPrefix() . "§c/tempban <player> <reason> <duration>");
            }
        } else {
            $sender->sendMessage(BanSystem::getPrefix() . BanSystem::NO_PERMS);
        }
        return true;
    }

    public function getOwningPlugin(): BanSystem {
        return BanSystem::getInstance();
    }
}