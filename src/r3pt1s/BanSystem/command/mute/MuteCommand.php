<?php

namespace r3pt1s\BanSystem\command\mute;

use pocketmine\plugin\PluginOwned;
use r3pt1s\BanSystem\BanSystem;
use r3pt1s\BanSystem\manager\mute\MuteManager;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\lang\Translatable;
use r3pt1s\BanSystem\provider\CurrentProvider;

class MuteCommand extends Command implements PluginOwned {

    public function __construct(string $name, Translatable|string $description = "", Translatable|string|null $usageMessage = null, array $aliases = []) {
        parent::__construct($name, $description, $usageMessage, $aliases);
        $this->setPermission("bansystem.mute.command");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool {
        if ($sender->hasPermission($this->getPermission())) {
            if (isset($args[0]) && isset($args[1])) {
                if (CurrentProvider::get()->isPlayerCreated($args[0])) {
                    if (!MuteManager::getInstance()->isMuted($args[0])) {
                        if (is_numeric($args[1])) {
                            if (MuteManager::getInstance()->isMuteId(intval($args[1]))) {
                                $sender->sendMessage(BanSystem::getPrefix() . "§7O jogador §e" . $args[0] . " §7foi mutado!");
                                MuteManager::getInstance()->mutePlayer($args[0], $sender, intval($args[1]));
                            } else {
                                $sender->sendMessage(BanSystem::getPrefix() . "§cFavor coloque um mute valido! Exemlpo: /mute DK9695 hack 1d");
                            }
                        } else {
                            $sender->sendMessage(BanSystem::getPrefix() . "§c/mute <player> <muteId>");
                        }
                    } else {
                        $sender->sendMessage(BanSystem::getPrefix() . "§7O jogador §e" . $args[0] . " §7já foi mutado!");
                    }
                } else {
                    $sender->sendMessage(BanSystem::getPrefix() . "§7O jogador não existe!");
                }
            } else {
                $sender->sendMessage(BanSystem::getPrefix() . "§c/mute <player> <banId>");
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