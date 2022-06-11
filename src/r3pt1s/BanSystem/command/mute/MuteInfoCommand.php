<?php

namespace r3pt1s\BanSystem\command\mute;

use pocketmine\plugin\PluginOwned;
use r3pt1s\BanSystem\BanSystem;
use r3pt1s\BanSystem\manager\mute\MuteManager;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\lang\Translatable;
use r3pt1s\BanSystem\provider\CurrentProvider;

class MuteInfoCommand extends Command implements PluginOwned {

    public function __construct(string $name, Translatable|string $description = "", Translatable|string|null $usageMessage = null, array $aliases = []) {
        parent::__construct($name, $description, $usageMessage, $aliases);
        $this->setPermission("bansystem.muteinfo.command");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool {
        if ($sender->hasPermission($this->getPermission())) {
            if (isset($args[0])) {
                if (CurrentProvider::get()->isPlayerCreated($args[0])) {
                    $sender->sendMessage(BanSystem::getPrefix() . "§7Informações sobre §e" . $args[0] . "§7:");
                    $sender->sendMessage(BanSystem::getPrefix() . "§7MutePoints: §e" . MuteManager::getInstance()->getMutesPoints($args[0]));
                    $sender->sendMessage(BanSystem::getPrefix() . "§7Muted: §e" . (MuteManager::getInstance()->isMuted($args[0]) ? "§cSIM" : "§aNÃO"));
                    $info = MuteManager::getInstance()->getMuteInfo($args[0]);
                    if ($info !== false) {
                        $reason = (isset($info["Id"]) ? BanSystem::getInstance()->getConfiguration()->getMuteIds()[$info["Id"]]["reason"] ?? "Error" : $info["Reason"] ?? "Error");
                        $sender->sendMessage(BanSystem::getPrefix() . "§7Motivo: §e" . $reason);
                        $sender->sendMessage(BanSystem::getPrefix() . "§7Moderador: §e" . $info["Moderator"]);
                        $sender->sendMessage(BanSystem::getPrefix() . "§7Tempo: §e" . ($info["Time"] == "-1" ? "PERMANENTLY" : $info["Time"]));
                        $sender->sendMessage(BanSystem::getPrefix() . "§7Mutado em: §e" . $info["MutedAt"]);
                    }
                } else {
                    $sender->sendMessage(BanSystem::getPrefix() . "§7O jogador §e" . $args[0] . " §7não existe!");
                }
            } else {
                $sender->sendMessage(BanSystem::getPrefix() . "§c/muteinfo <player>");
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