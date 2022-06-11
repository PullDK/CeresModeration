<?php

namespace r3pt1s\BanSystem\form\mute;

use dktapps\pmforms\MenuForm;
use dktapps\pmforms\MenuOption;
use pocketmine\player\Player;

class ViewMuteLogForm extends MenuForm {

    private array $options = [];

    public function __construct(array $muteLog, string $target) {
        $text = "§7Jogador: §e" . $target . "\n";
        $text .= "§7Moderador: §e" . $muteLog["Moderator"] . "\n";
        $text .= "§7Mutado em: §e" . $muteLog["MutedAt"] . "\n";
        $text .= "§7Motivo: §e" . $muteLog["Reason"];

        $this->options[] = new MenuOption("§cVoltar");

        parent::__construct("§c" . $muteLog["MutedAt"], $text, $this->options, function (Player $player, int $data) use($target): void {
            $player->sendForm(new MuteLogsForm($target));
        });
    }
}