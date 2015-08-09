<?php
namespace PushMe;

use pocketmine\plugin\PluginBase as Plugin;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\math\Vector3;
use pocketmine\entity\PrimedTNT;
use pocketmine\level\sound\FizzSound;
use pocketmine\level\sound\ClickSound;

class Loader extends Plugin implements Listener {
    public function onEnable() {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->getServer()->getLogger()->info("PushMe Starting");
    }

    public function onPlayerMove(PlayerMoveEvent $event) {
        $player = $event->getPlayer();
        $direction = $player->getDirectionVector();
        $x = $direction->getX();
        $z = $direction->getZ();
        $block = $player->getLevel()->getBlockIdAt($player->getX(), ($player->getY() - 0.1), $player->getZ());
        if($block === 152)
            if($player->hasPermission("pushme.use")) {
                for($i = 1; $i <= 1000; $i++) {
                    $player->knockBack($player, 0, $x, $z, 0.5);
                }
                $player->getLevel()->addSound(new FizzSound(new Vector3($player->getX(), $player->getY(), $player->getZ())));
            } else {
                $player->sendTip("I don wanna push you cuz you to noob for me");
                $player->getLevel()->addSound(new ClickSound(new Vector3($player->getX(), $player->getY(), $player->getZ())));
            }
    }

    public function onDisable() {
        $this->getServer()->getLogger()->info("PushMe Shutting down");
    }
}