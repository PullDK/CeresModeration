<?php

namespace r3pt1s\BanSystem\command\ban;

use pocketmine\plugin\PluginOwned;
use r3pt1s\BanSystem\BanSystem;
use r3pt1s\BanSystem\manager\ban\BanManager;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\lang\Translatable;
use r3pt1s\BanSystem\provider\CurrentProvider;

class BanCommand extends Command implements PluginOwned {

    public function __construct(string $name, Translatable|string $description = "", Translatable|string|null $usageMessage = null, array $aliases = []) {
        parent::__construct($name, $description, $usageMessage, $aliases);
        $this->setPermission("bansystem.ban.command");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool {
        if ($sender->hasPermission($this->getPermission())) {
            if (isset($args[0]) && isset($args[1])) {
                if (CurrentProvider::get()->isPlayerCreated($args[0])) {
                    if (!BanManager::getInstance()->isBanned($args[0])) {
                        if (is_numeric($args[1])) {
                            if (BanManager::getInstance()->isBanId(intval($args[1]))) {
                                $sender->sendMessage(BanSystem::getPrefix() . "§7O jogador §e" . $args[0] . " §7foi banido!");
                                BanManager::getInstance()->banPlayer($args[0], $sender, intval($args[1]));
                            } else {
                                $sender->sendMessage(BanSystem::getPrefix() . "§cFavor colcar um ban valido! Exemlpo: /ban DK9695 hack 1d");
                            }
                        } else {
                            $sender->sendMessage(BanSystem::getPrefix() . "§c/ban <jogador> <banId>");
                        }
                    } else {
                        $sender->sendMessage(BanSystem::getPrefix() . "§7O jogador §e" . $args[0] . " §7já foi banido!");
                    }
                } else {
                    $sender->sendMessage(BanSystem::getPrefix() . "§7O jogador não existe!");
                }
            } else {
                $sender->sendMessage(BanSystem::getPrefix() . "§c/ban <jogador> <banId>");
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