<?php

namespace r3pt1s\BanSystem\handler;

use r3pt1s\BanSystem\BanSystem;
use r3pt1s\BanSystem\manager\ban\BanManager;
use r3pt1s\BanSystem\utils\Utils;
use pocketmine\player\Player;

class BanHandler {

    private static self $instance;

    public function __construct() {
        self::$instance = $this;
    }

    public function handle(Player $player, ?string &$kickScreen = null): bool {
        if (BanManager::getInstance()->isBanned($player)) {
            if (!$player->hasPermission("bansystem.ban.bypass")) {
                if (!BanManager::getInstance()->isBanExpired($player)) {
                    $info = BanManager::getInstance()->getBanInfo($player);
                    if ($info !== false) {
                        $kickScreen = "§8» §cVocê foi banido(a)! §8«";
                        $kickScreen .= "\n§8» §cMotivo: §e" . ($info["Type"] == BanManager::TYPE_BAN ?
                                (
                                isset(BanSystem::getInstance()->getConfiguration()->getBanIds()[$info["Id"]]) ? BanSystem::getInstance()->getConfiguration()->getBanIds()[$info["Id"]]["reason"] : "Error"
                                ) : $info["Reason"]);
                        $kickScreen .= "\n§8» §cTempo: §e" . ($info["Time"] == "-1" ? "PERMANENTLY" : Utils::diffString(new \DateTime("now"), new \DateTime($info["Time"])));
                        return true;
                    }
                } else {
                    BanManager::getInstance()->unbanPlayer($player);
                }
            }
        }
        return false;
    }

    public static function getInstance(): BanHandler {
        return self::$instance;
    }
}