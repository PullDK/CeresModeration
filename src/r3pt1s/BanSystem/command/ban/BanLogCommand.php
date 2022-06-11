<?php

namespace r3pt1s\BanSystem\command\ban;

use pocketmine\plugin\PluginOwned;
use r3pt1s\BanSystem\BanSystem;
use r3pt1s\BanSystem\form\ban\BanLogsForm;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\lang\Translatable;
use pocketmine\player\Player;
use r3pt1s\BanSystem\provider\CurrentProvider;

class BanLogCommand extends Command implements PluginOwned {

    public function __construct(string $name, Translatable|string $description = "", Translatable|string|null $usageMessage = null, array $aliases = []) {
        parent::__construct($name, $description, $usageMessage, $aliases);
        $this->setPermission("bansystem.banlog.command");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool {
        if ($sender instanceof Player) {
            if ($sender->hasPermission($this->getPermission())) {
                if (isset($args[0])) {
                    if (CurrentProvider::get()->isPlayerCreated($args[0])) {
                        $sender->sendForm(new BanLogsForm($args[0]));
                    } else {
                        $sender->sendMessage(BanSystem::getPrefix() . "§7O jogador §e" . $args[0] . " §7não existe!");
                    }
                } else {
                    $sender->sendMessage(BanSystem::getPrefix() . "§c/banlog <player>");
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