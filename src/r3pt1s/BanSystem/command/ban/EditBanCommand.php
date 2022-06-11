<?php

namespace r3pt1s\BanSystem\command\ban;

use pocketmine\plugin\PluginOwned;
use r3pt1s\BanSystem\BanSystem;
use r3pt1s\BanSystem\manager\ban\BanManager;
use r3pt1s\BanSystem\utils\Utils;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\lang\Translatable;

class EditBanCommand extends Command implements PluginOwned {

    public function __construct(string $name, Translatable|string $description = "", Translatable|string|null $usageMessage = null, array $aliases = []) {
        parent::__construct($name, $description, $usageMessage, $aliases);
        $this->setPermission("bansystem.editban.command");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool {
        if ($sender->hasPermission($this->getPermission())) {
            if (isset($args[0]) && isset($args[1]) && isset($args[2])) {
                if (BanManager::getInstance()->isBanned($args[0])) {
                    if (Utils::convertStringToDateFormat($args[2]) === null) {
                        $sender->sendMessage(BanSystem::getPrefix() . "§cFavor colocar um banimento valido! Examplo: 1d");
                    } else {
                        if (strtolower($args[1]) == "add") {
                            if (!BanManager::getInstance()->editBan($args[0], $args[2], "add", $errorMessage)) {
                                $sender->sendMessage(BanSystem::getPrefix() . $errorMessage);
                            } else {
                                $sender->sendMessage(BanSystem::getPrefix() . "§7O ban do jogador §e" . $args[0] . " §7foi editado!");
                            }
                        } else if (strtolower($args[1]) == "sub") {
                            if (!BanManager::getInstance()->editBan($args[0], $args[2], "sub", $errorMessage)) {
                                $sender->sendMessage(BanSystem::getPrefix() . $errorMessage);
                            } else {
                                $sender->sendMessage(BanSystem::getPrefix() . "§7O ban do jogador §e" . $args[0] . " §7foi editado!");
                            }
                        } else {
                            $sender->sendMessage(BanSystem::getPrefix() . "§c/editban <player> <add | sub> <time>");
                        }
                    }
                } else {
                    $sender->sendMessage(BanSystem::getPrefix() . "§7O jogador §e" . $args[0] . " §7não foi banido!");
                }
            } else {
                $sender->sendMessage(BanSystem::getPrefix() . "§c/editban <player> <add | sub> <time>");
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