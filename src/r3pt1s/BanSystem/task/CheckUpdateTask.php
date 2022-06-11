<?php

namespace r3pt1s\BanSystem\task;

use r3pt1s\BanSystem\BanSystem;
use pocketmine\scheduler\AsyncTask;
use pocketmine\utils\Internet;

class CheckUpdateTask extends AsyncTask {

    public function onRun(): void {
        try {
            $curl = Internet::simpleCurl("https://raw.githubusercontent.com/r3pt1s/BanSystem/main/plugin.yml");
            $data = yaml_parse($curl->getBody());
            if ($data == false) {
                $this->setResult([false]);
            } else {
                if (isset($data["version"])) {
                    if (floatval($data["version"]) > BanSystem::$VERSION) {
                        $this->setResult([true, $data["version"], BanSystem::$VERSION]);
                    }
                }
            }
        } catch (\Exception $e) {
            $this->setResult([false]);
        }
    }

    public function onCompletion(): void {
        if (($this->getResult()[0] ?? false) == false) {
            \GlobalLogger::get()->info(BanSystem::getPrefix() . "§7PLUGIN ATUALIZADO RSRS!");
        } else {
            \GlobalLogger::get()->emergency(BanSystem::getPrefix() . "§cATUALIZA CORNO!");
            \GlobalLogger::get()->emergency(BanSystem::getPrefix() . "§cVERSÃO: §e" . $this->getResult()[1] . " §8| §cYour current version: §e" . $this->getResult()[2]);
        }
    }
}