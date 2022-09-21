<?php

namespace AutoClearMobs\Altay\Task;

use AutoClearMobs\Altay\AltayC;
use pocketmine\scheduler\Task;
use pocketmine\Server;

class AltayTask extends Task
{

    public $time;

    public function __construct(int $time = 5)
    {
        $this->time = $time;
    }

    public function onRun(): void
    {
        $this->time--;
        if ($this->time == 90 or $this->time == 60 or $this->time == 30 or $this->time == 15 or $this->time == 10 or $this->time == 5 or $this->time < 5) {
            foreach (Server::getInstance()->getOnlinePlayers() as $player) {
                if ($this->time > 15) {
                    $player->sendMessage("§7» §3All mobs clearing in " . gmdate("i", $this->time) . " Minute " . gmdate("s", $this->time) . " seconds");
                } else {
                    if ($this->time > 0) {
                        $player->sendPopup("§7» §3All mobs clearing in " . gmdate("i", $this->time) . " Minute " . gmdate("s", $this->time) . " seconds");
                    }
                    if ($this->time == 0) {
                        AltayC::getInstance()->clean();
                        $this->time = AltayC::getInstance()->getCleanTime();
                        $player->sendPopup("§7» §3All mobs cleared!");
                    }
                }
            }
        }
        if ($this->time == 0) { #if has no active players
            AltayC::getInstance()->clean();
            $this->time = AltayC::getInstance()->getCleanTime();
        }
    }
}