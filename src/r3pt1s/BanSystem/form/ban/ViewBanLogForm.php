<?php

namespace r3pt1s\BanSystem\form\ban;

use dktapps\pmforms\MenuForm;
use dktapps\pmforms\MenuOption;
use pocketmine\player\Player;

class ViewBanLogForm extends MenuForm {

    private array $options = [];

    public function __construct(array $banLog, string $target) {
        $text = "§7Jogador: §e" . $target . "\n";
        $text .= "§7Moderador: §e" . $banLog["Moderator"] . "\n";
        $text .= "§7Banido em: §e" . $banLog["BannedAt"] . "\n";
        $text .= "§7Motivo: §e" . $banLog["Reason"];

        $this->options[] = new MenuOption("§cVoltar");

        parent::__construct("§c" . $banLog["BannedAt"], $text, $this->options, function (Player $player, int $data) use($target): void {
            $player->sendForm(new BanLogsForm($target));
        });
    }
}