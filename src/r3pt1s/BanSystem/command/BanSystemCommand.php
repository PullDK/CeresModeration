<?php

namespace r3pt1s\BanSystem\command;

use pocketmine\plugin\PluginOwned;
use r3pt1s\BanSystem\BanSystem;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;

class BanSystemCommand extends Command implements PluginOwned {

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool {
        $sender->sendMessage(BanSystem::getPrefix() . "§7Plugin by §ePullDK§7:");
        $sender->sendMessage(BanSystem::getPrefix() . "§7Discord §ePullDK#1238§7:");
        return true;
    }

    public function getOwningPlugin(): BanSystem {
        return BanSystem::getInstance();
    }
}