<?php

namespace r3pt1s\BanSystem\form\warn;

use dktapps\pmforms\MenuForm;
use dktapps\pmforms\MenuOption;
use pocketmine\player\Player;

class ViewWarnForm extends MenuForm {

    private array $options = [];

    public function __construct(array $warnData, string $target) {
        $text = "§7Jogador: §e" . $target . "\n";
        $text .= "§7Moderador: §e" . $warnData["Moderator"] . "\n";
        $text .= "§7Avisado em: §e" . $warnData["WarnedAt"] . "\n";
        $text .= "§7Motivo: §e" . $warnData["Reason"];

        $this->options[] = new MenuOption("§cVoltar");

        parent::__construct("§c" . $warnData["WarnedAt"], $text, $this->options, function (Player $player, int $data) use($target): void {
            $player->sendForm(new WarnsForm($target));
        });
    }
}