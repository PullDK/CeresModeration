<?php

namespace r3pt1s\BanSystem\command\warn;

use pocketmine\plugin\PluginOwned;
use r3pt1s\BanSystem\BanSystem;
use r3pt1s\BanSystem\manager\warn\WarnManager;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\lang\Translatable;
use pocketmine\Server;
use r3pt1s\BanSystem\provider\CurrentProvider;

class WarnCommand extends Command implements PluginOwned {

    public function __construct(string $name, Translatable|string $description = "", Translatable|string|null $usageMessage = null, array $aliases = []) {
        parent::__construct($name, $description, $usageMessage, $aliases);
        $this->setPermission("bansystem.warn.command");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool {
        if ($sender->hasPermission($this->getPermission())) {
            if (count($args) == 0) {
                $sender->sendMessage(BanSystem::getPrefix() . "§c/warn <player> <reason>");
                return false;
            }

            $player = array_shift($args);
            $reason = trim(implode(" ", $args));

            if (CurrentProvider::get()->isPlayerCreated($player)) {
                $sender->sendMessage(BanSystem::getPrefix() . "§7O jogador §e" . $player . " §7foi avisado!");
                WarnManager::getInstance()->warnPlayer($player, $sender->getName(), ($reason == "" ? "Sem motivo." : $reason));

                if (($player = Server::getInstance()->getPlayerByPrefix($player)) !== null) {
                    $player->sendMessage(BanSystem::getPrefix() . "§cVocê recebeu um aviso!");
                    $player->sendMessage(BanSystem::getPrefix() . "§7Motivo: §e" . ($reason == "" ? "sem motivo." : $reason));
                }
            } else {
                $sender->sendMessage(BanSystem::getPrefix() . "§7O jogador não existe!");
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