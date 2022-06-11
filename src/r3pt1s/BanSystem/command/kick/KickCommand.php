<?php

namespace r3pt1s\BanSystem\command\kick;

use pocketmine\plugin\PluginOwned;
use r3pt1s\BanSystem\BanSystem;
use r3pt1s\BanSystem\manager\notify\NotifyManager;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\lang\Translatable;
use pocketmine\Server;

class KickCommand extends Command implements PluginOwned {

    public function __construct(string $name, Translatable|string $description = "", Translatable|string|null $usageMessage = null, array $aliases = []) {
        parent::__construct($name, $description, $usageMessage, $aliases);
        $this->setPermission("bansystem.kick.command");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool {
        if ($sender->hasPermission($this->getPermission())) {
            if (count($args) == 0) {
                $sender->sendMessage(BanSystem::getPrefix() . "§c/kick <player> <reason>");
                return false;
            }

            $player = array_shift($args);
            $reason = trim(implode(" ", $args));

            if (($player = Server::getInstance()->getPlayerByPrefix($player)) !== null) {
                $sender->sendMessage(BanSystem::getPrefix() . "§7O jogador §e" . $player->getName() . " §7foi kickado!");
                $kickScreen = "§8» §cVocê foi kickado! §8«";
                $kickScreen .= "\n§8» §cMotivo: §e" . ($reason == "" ? "Sem motivo." : $reason);
                $player->kick($kickScreen);

                NotifyManager::sendNotify(
                    BanSystem::getPrefix() . "§e" . $player->getName() . " §7Foi kickado por §c" . $sender->getName() . "§7!\n" .
                    BanSystem::getPrefix() . "§7Motivo: §e" . ($reason == "" ? "Sem motivo." : $reason)
                );
            } else {
                $sender->sendMessage(BanSystem::getPrefix() . "§7O jogador §e" . $args[0] . " §7Não está online!");
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