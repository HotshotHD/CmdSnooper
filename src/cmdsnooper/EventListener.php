<?php

namespace cmdsnooper;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerCommandPreprocessEvent;
use cmdsnooper\CmdSnooper;

class EventListener implements Listener {

    public $plugin;

    public function __construct(CmdSnooper $plugin) {
        $this->plugin = $plugin;
    }

    public function getPlugin() {
        return $this->plugin;
    }

    public function onPlayerCmd(PlayerCommandPreprocessEvent $event) {
        $sender = $event->getPlayer();
        $msg = $event->getMessage();

        if ($this->getPlugin()->cfg->get("Console.Logger") == "true") {
            if ($msg[0] == "/") {

                $hasTell = stripos($msg, "tell") && $this->getPlugin()->cfg->get("Console.HideTell");

//                            if($this->getPlugin()->cfg->get("Console.HideTell") == "true") {
//                            $hasTell = true;
//                            }
                if (!($hasTell || stripos($msg, "log") || stripos($msg, "reg") || stripos($msg, "pwd"))) {
                    $this->getPlugin()->getLogger()->info($sender->getName() . " > " . $msg);
                }
            }
        }

        if (!empty($this->getPlugin()->snoopers)) {

            $hasTell = stripos($msg, "tell") && $this->getPlugin()->cfg->get("Game.HideTell");

            foreach ($this->getPlugin()->snoopers as $snooper) {
                if ($msg[0] == "/") {
                    if (!($hasTell || stripos($msg, "log") || stripos($msg, "reg") || stripos($msg, "chpwd"))) {
                        $snooper->sendMessage($sender->getName() . " > " . $msg);
                    }
                }
            }
        }
    }

}
